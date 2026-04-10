<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\LoteFacturas;
use App\Models\NroFactura;
use App\Models\FacturaRecibida;
use ZipArchive;
use File;
use App\Traits\Basic;
use App\Models\User;
use App\Jobs\MailLotesJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Helpers\GestorHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Helpers\DocumentColumnsHelper;
use App\Helpers\AlbaranTemplateHelper;
use App\Models\Recibo;
use App\Models\SystemParam;
use App\Enums\ParamSystemEnum;

class LoteController extends Controller
{
     public function enviarFacturas(Request $request, $idUser = null)
     {
          $effectiveUserId = GestorHelper::getUserId($request, $idUser);
          
          if (!$effectiveUserId) {
              return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
          }

          $user = User::where('id', $effectiveUserId)->first();
          $emails = $request->form['emails']; //array
          $description = $request->form['descripcion'];
          $tipo_factura = $request->form['tipo_factura']['tipo'];

          $data = [
               'user' => $user,
               'emails' => $emails,
               'des' =>  $description,
               'tipo' => $tipo_factura,
          ];

          dispatch(new MailLotesJob($data));

          return response()->json('enviado con exito', 200);
     }

     public function getFacturasByRango(Request $request, $user_id = null, $desde = null, $hasta = null, $tipo = null)
     {
          $effectiveUserId = GestorHelper::getUserId($request, $user_id);
          
          if (!$effectiveUserId) {
              return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
          }
          
          // Validar y parsear fechas
          try {
               if ($desde) {
                    $desde = Carbon::parse($desde)->format('Y-m-d');
               }
               if ($hasta) {
                    $hasta = Carbon::parse($hasta)->format('Y-m-d');
               }
          } catch (\Exception $e) {
               return response()->json(['error' => 'Formato de fecha inválido'], 400);
          }
          
          if (!$desde || !$hasta) {
               return response()->json(['error' => 'Las fechas desde y hasta son requeridas'], 400);
          }
          
          User::find($effectiveUserId)->update(['filetoken' => Str::random(20)]);
          return  $this->switchFact($effectiveUserId, $desde, $hasta, $tipo);
     }

     private function crearZip($lista_facturas, $userId, $tipo)
     {
          if (empty($lista_facturas)) {
               throw new \Exception('No hay facturas para procesar.');
          }

          $zip = new ZipArchive();
          $fileName = 'facturas.zip';

          if ($tipo == 'Facturas Enviadas') {
               $fileName = 'facturas_enviadas.zip';
          } elseif ($tipo == 'Facturas Recibidas') {
               $fileName = 'facturas_recibidas.zip';
          }

        // Asegurarse de que el directorio base 'lotes' existe
        $baseDirectory = storage_path('app/public/lotes');
        if (!file_exists($baseDirectory)) {
             mkdir($baseDirectory, 0775, true);
             Log::info('Directorio base lotes creado', ['path' => $baseDirectory]);
        }
        
        $directoryRelative = 'userId_' . $userId;
        $directoryPath = storage_path('app/public/lotes/' . $directoryRelative);
        
        // Crear el directorio del usuario si no existe
        if (!file_exists($directoryPath)) {
             mkdir($directoryPath, 0775, true);
             Log::info('Directorio de usuario creado', ['path' => $directoryPath]);
        }
        
        // También usar Storage disk para asegurar consistencia
        if (!Storage::disk('lotes')->exists($directoryRelative)) {
             Storage::disk('lotes')->makeDirectory($directoryRelative);
             @chmod($directoryPath, 0775);
        }
        
        $zipPath = $directoryPath . '/' . $fileName;

          if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
               throw new \Exception('Error: No se pudo crear el archivo ZIP.');
          }

          // Cargar el usuario para generar PDFs si es necesario
          $user = User::where('id', $userId)->with(['provincia', 'metodos_pago'])->first();
          if ($user) {
               // Obtener logo en base64 para el PDF
               $user->logo_base64 = $this->getLogoBase64($user->avatar ?? '');
          }

          $archivosAñadidos = 0;
          $pdfsGenerados = [];

          foreach ($lista_facturas as $key => $factura) {
               // Acceder a recibo como objeto, no como array
               $recibo = $factura->recibo ?? null;
               $sourceFileName = $recibo ? ($recibo->factura_url ?? null) : null;

               if (!$sourceFileName || !$recibo) {
                    Log::warning('Factura sin factura_url o recibo', [
                         'factura_id' => $factura->id ?? null,
                         'recibo_id' => $recibo->id ?? null
                    ]);
                    continue;
               }

               // Usar Storage disk para obtener la ruta correcta
               $relativePath = 'userId_' . $userId . '/' . $sourceFileName;
               $sourceFilePath = null;
               
               // Verificar si el archivo existe
               $storageExists = Storage::disk('recibos')->exists($relativePath);
               
               if ($storageExists) {
                    // Obtener la ruta física completa usando Storage
                    $sourceFilePath = Storage::disk('recibos')->path($relativePath);
                    
                    if (file_exists($sourceFilePath) && is_readable($sourceFilePath)) {
                         // Archivo existe, usarlo directamente
                         if ($zip->addFile($sourceFilePath, $sourceFileName)) {
                              $archivosAñadidos++;
                              Log::debug('Archivo añadido al ZIP desde storage', [
                                   'recibo_id' => $recibo->id,
                                   'sourceFileName' => $sourceFileName
                              ]);
                         }
                         continue;
                    } else {
                         Log::info('Storage reporta que existe pero file_exists falla', [
                              'recibo_id' => $recibo->id,
                              'relativePath' => $relativePath,
                              'sourceFilePath' => $sourceFilePath,
                              'file_exists' => file_exists($sourceFilePath),
                              'is_readable' => is_readable($sourceFilePath)
                         ]);
                    }
               } else {
                    Log::info('Archivo no existe en Storage disk', [
                         'recibo_id' => $recibo->id,
                         'relativePath' => $relativePath,
                         'sourceFileName' => $sourceFileName
                    ]);
               }

               // Si no existe, generar el PDF dinámicamente
               // Obtener el número de factura correctamente
               // $factura es un NroFactura, que tiene el atributo nro_factura directamente
               // También puede accederse desde recibo->nro_factura si está cargado
               $nroFactura = $factura->nro_factura ?? ($factura->recibo->nro_factura->nro_factura ?? 0);
               
               // Si aún es 0, intentar cargar la relación desde recibo
               if ($nroFactura == 0 && $recibo && !$recibo->relationLoaded('nro_factura')) {
                    $recibo->load('nro_factura');
                    $nroFactura = $recibo->nro_factura->nro_factura ?? $factura->nro_factura ?? 0;
               }
               
               Log::info('Generando PDF para factura que no existe', [
                    'recibo_id' => $recibo->id,
                    'factura_id' => $factura->id ?? null,
                    'factura_url' => $sourceFileName,
                    'nro_factura' => $nroFactura,
                    'storageExists' => $storageExists,
                    'factura_nro_factura' => $factura->nro_factura ?? null,
                    'recibo_nro_factura' => $recibo->nro_factura->nro_factura ?? null
               ]);

               try {
                    // Cargar servicios del recibo si no están cargados
                    if (!$recibo->relationLoaded('servicios')) {
                         $recibo->load('servicios');
                    }
                    if (!$recibo->relationLoaded('cliente')) {
                         $recibo->load('cliente');
                    }

                    // Generar PDF
                    $pdfFileName = $this->generarPdfParaFactura($recibo, $nroFactura, $userId, $user);
                    if ($pdfFileName) {
                         $pdfsGenerados[] = $pdfFileName;
                         $relativePath = 'userId_' . $userId . '/' . $pdfFileName;
                         $sourceFilePath = Storage::disk('recibos')->path($relativePath);
                         
                         if (file_exists($sourceFilePath) && $zip->addFile($sourceFilePath, $pdfFileName)) {
                              $archivosAñadidos++;
                         }
                    }
               } catch (\Exception $e) {
                    Log::error('Error generando PDF para factura', [
                         'recibo_id' => $recibo->id,
                         'error' => $e->getMessage()
                    ]);
               }
          }

          $zip->close();

          // Verificar que el ZIP se haya creado correctamente
          if (!file_exists($zipPath)) {
               Log::error('No se pudo crear el ZIP de facturas enviadas', [
                    'zipPath' => $zipPath,
                    'archivosAñadidos' => $archivosAñadidos,
                    'directory_exists' => file_exists($directoryPath),
                    'directory_writable' => is_writable($directoryPath)
               ]);
               throw new \Exception('Error: No se pudo crear el archivo ZIP.');
          }

          // Si no se añadió ningún archivo, eliminar el ZIP vacío
          if ($archivosAñadidos === 0) {
               if (file_exists($zipPath)) {
                    unlink($zipPath);
               }
               Log::warning('ZIP de facturas enviadas eliminado porque estaba vacío', [
                    'zipPath' => $zipPath,
                    'total_facturas' => count($lista_facturas)
               ]);
               throw new \Exception('No se pudieron añadir archivos al ZIP. Verifique que los archivos existan y sean legibles.');
          }

          // Asegurar que el archivo sea accesible
          @chmod($zipPath, 0644);

          // Verificar que el archivo existe y tiene tamaño
          $zipSize = file_exists($zipPath) ? filesize($zipPath) : 0;
          Log::info('ZIP de facturas enviadas creado exitosamente', [
               'zipPath' => $zipPath,
               'archivosAñadidos' => $archivosAñadidos,
               'zipSize' => $zipSize,
               'exists' => file_exists($zipPath)
          ]);

          return response()->json([
               'message' => 'Proceso finalizado.',
               'fileName' => $fileName,
               'archivosAñadidos' => $archivosAñadidos,
               'pdfsGenerados' => count($pdfsGenerados)
          ], 200);
     }

     private function generarPdfParaFactura(Recibo $recibo, $nro_factura, $userId, $user)
     {
          try {
               // Asegurar que el recibo tenga todas las relaciones necesarias
               if (!$recibo->relationLoaded('servicios')) {
                    $recibo->load('servicios');
               }
               if (!$recibo->relationLoaded('cliente')) {
                    $recibo->load('cliente');
               }
               
               // Obtener template
               $template = SystemParam::where('business_id', $userId)
                    ->where('param', \App\Enums\ParamSystemEnum::ALBARAN_TEMPLATE->value)
                    ->value('value') ?? 'classic';
               
               $templateKey = AlbaranTemplateHelper::normalizeTemplate($template);
               $viewName = AlbaranTemplateHelper::getViewName($templateKey);
               $fecha = Carbon::parse($recibo->fecha)->format('d-m-Y');
               
               // Obtener columnas
               $columns = DocumentColumnsHelper::getForBusiness($userId);
               $columnsForType = DocumentColumnsHelper::filterByDocType($columns, 'factura');
               
               // Generar PDF
               $pdf = null;
               if ($templateKey !== 'classic') {
                    // Template moderno - formatear los datos
                    $lineas = $recibo->servicios ?? [];
                    $lineas = collect($lineas)->map(function ($servicio) {
                         if ($servicio->metadata && is_array($servicio->metadata)) {
                              foreach ($servicio->metadata as $key => $value) {
                                   $servicio->setAttribute($key, $value);
                              }
                         }
                         return $servicio;
                    })->toArray();
                    
                    $total = $recibo->total ?? collect($lineas)->sum(function ($s) {
                         return ($s->importe ?? $s->total ?? 0);
                    });
                    $invoice_footer = $user->invoiceFooter()?->value ?? '';
                    
                    $pdf = PDF::loadView($viewName, [
                         'data' => $lineas,
                         'recibo' => $recibo,
                         'userLog' => $user,
                         'total' => $total,
                         'nro_factura' => $nro_factura,
                         'fecha_emision' => Carbon::parse($recibo->fecha)->format('d/m/Y'),
                         'cliente' => $recibo->cliente,
                         'tipo' => 'factura',
                         'invoice_footer' => $invoice_footer,
                         'documentColumns' => $columnsForType,
                    ]);
               } else {
                    // Classic template
                    if ($recibo->servicios) {
                         $recibo->servicios = collect($recibo->servicios)->map(function ($servicio) {
                              if ($servicio->metadata && is_array($servicio->metadata)) {
                                   foreach ($servicio->metadata as $key => $value) {
                                        $servicio->$key = $value;
                                   }
                              }
                              return $servicio;
                         });
                    }
                    $pdf = PDF::loadView($viewName, [
                         'recibo' => $recibo,
                         'fecha' => $fecha,
                         'userLog' => $user,
                         'nro_factura' => $nro_factura,
                         'tipo' => 'factura',
                         'documentColumns' => $columnsForType,
                    ]);
               }
               
               $pdf->setPaper('a4', 'portrait');
               
               $nro = str_pad($nro_factura, 4, '0', STR_PAD_LEFT);
               $nombre_archivo = Carbon::now()->valueOf() . '_FACTURA_' . $nro . '.pdf';
               
               // Guardar PDF
               $pdfOutput = $pdf->output();
               Storage::disk('recibos')->put('userId_' . $userId . '/' . $nombre_archivo, $pdfOutput);
               
               Log::info('PDF generado exitosamente para factura enviada', [
                    'recibo_id' => $recibo->id,
                    'nro_factura' => $nro_factura,
                    'nombre_archivo' => $nombre_archivo
               ]);
               
               return $nombre_archivo;
          } catch (\Exception $e) {
               Log::error('Error en generarPdfParaFactura', [
                    'recibo_id' => $recibo->id,
                    'nro_factura' => $nro_factura,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
               ]);
               return null;
          }
     }

     private function formatearDatosParaPdf(Recibo $recibo)
     {
          // Formatear datos para templates modernos
          $lineas = collect($recibo->servicios ?? [])->map(function ($servicio) {
               return [
                    'descripcion' => $servicio->descripcion ?? '',
                    'cantidad' => $servicio->cantidad ?? 0,
                    'precio' => $servicio->precio ?? 0,
                    'total' => $servicio->total ?? 0,
               ];
          })->toArray();
          
          return [
               'data' => $lineas,
               'total' => $recibo->total ?? 0,
               'nro_factura' => $recibo->nro_factura->nro_factura ?? '',
               'fecha_emision' => Carbon::parse($recibo->fecha)->format('d/m/Y'),
               'cliente' => $recibo->cliente,
          ];
     }

     private function getLogoBase64($logoUrl)
     {
          try {
               if ($logoUrl && $logoUrl != "") {
                    $logoPath = storage_path("app/" . $logoUrl);
                    if (file_exists($logoPath)) {
                         $binaryData = file_get_contents($logoPath);
                         if ($binaryData !== false) {
                              $finfo = new \finfo(FILEINFO_MIME_TYPE);
                              $mimeType = $finfo->buffer($binaryData);
                              return 'data:' . $mimeType . ';base64,' . base64_encode($binaryData);
                         }
                    }
               }
               return '';
          } catch (\Exception $e) {
               Log::error('Error al procesar el logo: ' . $e->getMessage());
               return '';
          }
     }

     public function switchFact($userId, $desde, $hasta, $tipo)
     {

          $tipoFact = $tipo;
          $enviadas = null;
          $recibidas = null;
          $todas = null;
          switch ($tipoFact) {
               case "Todas":
                    $todas = $this->caseTodas($userId, $desde, $hasta, $tipo);
                    break;
               case "Facturas Enviadas":
                    $enviadas = $this->caseEnviadas($userId, $desde, $hasta, $tipo);
                    break;
               case "Facturas Recibidas":
                    $recibidas = $this->caseRecibidas($userId, $desde, $hasta, $tipo);
                    break;
               default:
                    throw new \Exception("Not supported");
          }

          return response()->json([
               'enviadas' => $enviadas,
               'recibidas' => $recibidas,
               'todas' => $todas,
               'user' => $userId
          ]);
     }


     public function caseTodas($userId, $desde, $hasta, $tipo)
     {
          $enviadas      = $this->caseEnviadas($userId, $desde, $hasta, $tipo);
          $recibidas     = $this->caseRecibidas($userId, $desde, $hasta, $tipo);
          return [
               'enviadas' => $enviadas,
               'recibidas' => $recibidas,
               'todas' => true
          ];
     }

     public function caseEnviadas($userId, $desde, $hasta, $tipo)
     {

          if (Storage::disk('lotes')->exists('userId_' . $userId . '/facturas_enviadas.zip')) {
               Storage::disk('lotes')->delete('userId_' . $userId . '/facturas_enviadas.zip');
          }

          if ($desde != null && $hasta != null) {
               $facturas = NroFactura::where('user_id', '=', $userId)->whereHas('recibo', function ($query) use ($desde, $hasta) {
                    $query->whereBetween('fecha', [$desde, $hasta]);
               })->with(['recibo.servicios', 'recibo.cliente'])->get();
               
               if ($facturas->isEmpty()) {
                    return [
                         'enviadas' => false,
                         'archivo' => null,
                         'facturasEnviadasGet' => [],
                         'message' => 'No se encontraron facturas enviadas en el rango de fechas especificado.'
                    ];
               }
               
               try {
                    $archivo = $this->crearZip($facturas, $userId, 'Facturas Enviadas');
                    
                    // Verificar que el ZIP realmente existe
                    $zipPath = storage_path('app/public/lotes/userId_' . $userId . '/facturas_enviadas.zip');
                    
                    // Esperar un momento para que el sistema de archivos actualice
                    $intentos = 0;
                    $maxIntentos = 5;
                    while (!file_exists($zipPath) && $intentos < $maxIntentos) {
                         usleep(200000); // Esperar 200ms
                         $intentos++;
                         clearstatcache(true, $zipPath); // Limpiar cache de stats
                    }
                    
                    if (!file_exists($zipPath)) {
                         Log::error('El ZIP no existe después de crearlo y esperar', [
                              'zipPath' => $zipPath,
                              'userId' => $userId,
                              'intentos' => $intentos,
                              'directory_exists' => file_exists(dirname($zipPath)),
                              'directory_writable' => is_writable(dirname($zipPath))
                         ]);
                         return [
                              'enviadas' => false,
                              'archivo' => null,
                              'facturasEnviadasGet' => $facturas,
                              'totalFacturas' => $facturas->count(),
                              'message' => 'Error: No se pudo crear el archivo ZIP.'
                         ];
                    }

                    // Verificar también usando Storage disk
                    $storagePath = 'userId_' . $userId . '/facturas_enviadas.zip';
                    if (!Storage::disk('lotes')->exists($storagePath)) {
                         Log::error('El ZIP no existe en Storage disk', [
                              'storagePath' => $storagePath,
                              'zipPath' => $zipPath,
                              'file_exists' => file_exists($zipPath)
                         ]);
                    }

                    return [
                         'enviadas' => true,
                         'archivo' => 'facturas_enviadas.zip',
                         'facturasEnviadasGet' => $facturas,
                         'totalFacturas' => $facturas->count(),
                    ];
               } catch (\Exception $e) {
                    Log::error('Error al crear ZIP de facturas enviadas', [
                         'error' => $e->getMessage(),
                         'userId' => $userId,
                         'totalFacturas' => $facturas->count()
                    ]);
                    return [
                         'enviadas' => false,
                         'archivo' => null,
                         'facturasEnviadasGet' => $facturas,
                         'totalFacturas' => $facturas->count(),
                         'message' => $e->getMessage()
                    ];
               }
          }
          return [
               'enviadas' => false,
               'archivo' => null,
               'facturasEnviadasGet' => [],
               'message' => 'Las fechas son requeridas.'
          ];
     }

     public function caseRecibidas($userId, $desde, $hasta, $tipo)
     {
          if (Storage::disk('lotes')->exists('userId_' . $userId . '/facturas_recibidas.zip')) {
               Storage::disk('lotes')->delete('userId_' . $userId . '/facturas_recibidas.zip');
          }
          
          $facRecibidas = FacturaRecibida::where("fecha", ">=", $desde)
               ->where("fecha", "<=", $hasta)
               ->where("user_id", "=", $userId)
               ->orderBy('id', 'desc')->get();

          if ($facRecibidas->isEmpty()) {
               return [
                    'recibidas' => false,
                    'archivo' => null,
                    'facturasRecibidasGet' => [],
                    'message' => 'No se encontraron facturas recibidas en el rango de fechas especificado.'
               ];
          }

          // Cargar relaciones de proveedor y items antes de procesar
          $facRecibidas->load(['proveedor.provincia', 'items']);
          
          // Obtener información del usuario para el PDF
          $user = User::where('id', $userId)->with('provincia')->first();
          
          // Log detallado de cada factura para debugging
          $facturasInfo = [];
          foreach ($facRecibidas as $facturaRecibida) {
               $facturasInfo[] = [
                    'id' => $facturaRecibida->id,
                    'imagen_raw' => $facturaRecibida->imagen,
                    'imagen_type' => gettype($facturaRecibida->imagen),
                    'imagen_is_null' => $facturaRecibida->imagen === null,
               ];
          }
          \Log::info('Facturas recibidas - datos crudos', [
               'facturas' => $facturasInfo
          ]);
          
          $arrayImagenes = [];
          foreach ($facRecibidas as $facturaRecibida) {
               $imagenDecoded = json_decode($facturaRecibida->imagen, true);
               
               // Log para cada factura
               \Log::info('Procesando factura recibida', [
                    'factura_id' => $facturaRecibida->id,
                    'imagen_original' => $facturaRecibida->imagen,
                    'imagen_decoded' => $imagenDecoded,
                    'is_array' => is_array($imagenDecoded),
               ]);
               
               if ($imagenDecoded && is_array($imagenDecoded)) {
                    $arrayImagenes[] = $imagenDecoded;
               }
          }
          
          $imagesNames = [];
          foreach ($arrayImagenes as $imagenName) {
               if ($imagenName && is_array($imagenName)) {
                    foreach ($imagenName as $image) {
                         if ($image && is_string($image)) {
                              $imagesNames[] = $image;
                         }
                    }
               }
          }

          // Log para debugging
          \Log::info('Facturas recibidas procesadas', [
               'total_facturas' => $facRecibidas->count(),
               'total_imagenes_encontradas' => count($imagesNames),
               'images_names' => $imagesNames,
               'array_imagenes_count' => count($arrayImagenes)
          ]);

          // Crear directorio para almacenar PDFs generados temporalmente
          $directoryRelative = 'userId_' . $userId;
          $recibosPath = storage_path('app/public/recibos/userId_' . $userId . '/');
          if (!file_exists($recibosPath)) {
               mkdir($recibosPath, 0755, true);
          }
          
          // Generar PDFs para facturas que no tienen archivos subidos
          $pdfsGenerados = [];
          foreach ($facRecibidas as $facturaRecibida) {
               $imagenDecoded = json_decode($facturaRecibida->imagen, true);
               $tieneArchivos = $imagenDecoded && is_array($imagenDecoded) && count($imagenDecoded) > 0;
               
               if (!$tieneArchivos) {
                    // Generar PDF para esta factura
                    try {
                         $nombrePdf = Carbon::now()->valueOf() . '_FACTURA_RECIBIDA_' . str_pad($facturaRecibida->id, 4, '0', STR_PAD_LEFT) . '.pdf';
                         $pdfPath = $recibosPath . $nombrePdf;
                         
                         // Cargar items si no están cargados
                         if (!$facturaRecibida->relationLoaded('items')) {
                              $facturaRecibida->load('items');
                         }
                         
                         $pdf = PDF::loadView('pdf.factura_recibida_individual', [
                              'factura' => $facturaRecibida,
                              'items' => $facturaRecibida->items,
                              'userLog' => $user,
                              'tituloPdf' => 'FACTURA',
                         ])->setPaper('a4', 'portrait');
                         
                         file_put_contents($pdfPath, $pdf->output());
                         $pdfsGenerados[] = $nombrePdf;
                         
                         Log::info('PDF generado para factura recibida', [
                              'factura_id' => $facturaRecibida->id,
                              'pdf_path' => $pdfPath
                         ]);
                    } catch (\Exception $e) {
                         Log::error('Error generando PDF para factura recibida', [
                              'factura_id' => $facturaRecibida->id,
                              'error' => $e->getMessage()
                         ]);
                    }
               }
          }

          // Combinar archivos subidos con PDFs generados
          $todosLosArchivos = array_merge($imagesNames, $pdfsGenerados);
          
          Log::info('Archivos para ZIP', [
               'archivos_subidos' => count($imagesNames),
               'pdfs_generados' => count($pdfsGenerados),
               'total_archivos' => count($todosLosArchivos)
          ]);

          // Si no hay archivos (ni subidos ni generados), no crear ZIP
          if (empty($todosLosArchivos)) {
               return [
                    'recibidas' => true,
                    'archivo' => null,
                    'facturasRecibidasGet' => $facRecibidas->map(function($factura) {
                         return [
                              'id' => $factura->id,
                              'nro_factura' => $factura->nro_factura,
                              'fecha' => $factura->fecha,
                              'total' => $factura->total,
                              'proveedor' => $factura->proveedor ? [
                                   'id' => $factura->proveedor->id,
                                   'nombre' => $factura->proveedor->nombre,
                                   'cif' => $factura->proveedor->cif,
                              ] : null,
                              'archivos' => json_decode($factura->imagen, true) ?? [],
                         ];
                    })->toArray(),
                    'totalFacturas' => $facRecibidas->count(),
                    'archivosAñadidos' => 0,
                    'hasArchivos' => false,
                    'message' => "No se pudieron generar archivos para las facturas recibidas."
               ];
          }

          // Crear el ZIP
          $zip = new ZipArchive();
          $fileName = 'facturas_recibidas.zip';
          
          // Asegurarse de que el directorio base 'lotes' existe
          $baseDirectory = storage_path('app/public/lotes');
          if (!file_exists($baseDirectory)) {
               mkdir($baseDirectory, 0775, true);
               Log::info('Directorio base lotes creado', ['path' => $baseDirectory]);
          }
          
          $directoryRelative = 'userId_' . $userId;
          $directoryPath = storage_path('app/public/lotes/' . $directoryRelative);
          
          // Crear el directorio del usuario si no existe
          if (!file_exists($directoryPath)) {
               mkdir($directoryPath, 0775, true);
               Log::info('Directorio de usuario creado para facturas recibidas', ['path' => $directoryPath]);
          }

          // También usar Storage disk para asegurar consistencia
          if (!Storage::disk('lotes')->exists($directoryRelative)) {
               Storage::disk('lotes')->makeDirectory($directoryRelative);
               @chmod($directoryPath, 0775);
          }
          
          $zipPath = $directoryPath . '/' . $fileName;

          if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
               return [
                    'recibidas' => false,
                    'archivo' => null,
                    'facturasRecibidasGet' => [],
                    'message' => 'Error: No se pudo crear el archivo ZIP.'
               ];
          }

          $archivosAñadidos = 0;
          foreach ($todosLosArchivos as $archivo) {
               $sourceFilePath = storage_path('app/public/recibos/userId_' . $userId . '/' . $archivo);

               if (!file_exists($sourceFilePath)) {
                    Log::warning('Archivo no encontrado para añadir al ZIP', [
                         'archivo' => $archivo,
                         'path' => $sourceFilePath
                    ]);
                    continue;
               }

               if (!is_readable($sourceFilePath)) {
                    Log::warning('Archivo no legible para añadir al ZIP', [
                         'archivo' => $archivo,
                         'path' => $sourceFilePath
                    ]);
                    continue;
               }

               if ($zip->addFile($sourceFilePath, $archivo)) {
                    $archivosAñadidos++;
               }
          }

          $zip->close();

          // Verificar que el ZIP se haya creado correctamente
          if (!file_exists($zipPath)) {
               Log::error('No se pudo crear el ZIP de facturas recibidas', [
                    'zipPath' => $zipPath,
                    'directory_exists' => file_exists($directoryPath),
                    'directory_writable' => is_writable($directoryPath)
               ]);
               return [
                    'recibidas' => false,
                    'archivo' => null,
                    'facturasRecibidasGet' => [],
                    'message' => 'Error: No se pudo crear el archivo ZIP.'
               ];
          }

          if ($archivosAñadidos === 0) {
               // Si no se añadió ningún archivo, eliminar el ZIP vacío
               if (file_exists($zipPath)) {
                    unlink($zipPath);
               }
               Log::warning('ZIP de facturas recibidas eliminado porque estaba vacío', [
                    'zipPath' => $zipPath,
                    'imagesNames_count' => count($imagesNames),
                    'facturas_count' => $facRecibidas->count()
               ]);
               return [
                    'recibidas' => false,
                    'archivo' => null,
                    'facturasRecibidasGet' => [],
                    'message' => 'No se pudieron añadir archivos al ZIP. Verifique que los archivos existan y sean legibles.'
               ];
          }

          // Asegurar que el archivo sea accesible
          @chmod($zipPath, 0644);

          // Verificar que el archivo existe y tiene tamaño
          $zipSize = file_exists($zipPath) ? filesize($zipPath) : 0;
          Log::info('ZIP de facturas recibidas creado exitosamente', [
               'zipPath' => $zipPath,
               'archivosAñadidos' => $archivosAñadidos,
               'zipSize' => $zipSize,
               'exists' => file_exists($zipPath)
          ]);

          // Cargar relaciones de proveedor para devolver información completa
          $facRecibidas->load('proveedor');
          
          $mensaje = "Se generó el ZIP con {$archivosAñadidos} archivo(s).";
          if (count($pdfsGenerados) > 0) {
               $mensaje .= " Se generaron " . count($pdfsGenerados) . " PDF(s) automáticamente para facturas sin archivos subidos.";
          }
          
          return [
               'recibidas' => true,
               'archivo' => $fileName,
               'facturasRecibidasGet' => $facRecibidas->map(function($factura) {
                    return [
                         'id' => $factura->id,
                         'nro_factura' => $factura->nro_factura,
                         'fecha' => $factura->fecha,
                         'total' => $factura->total,
                         'proveedor' => $factura->proveedor ? [
                              'id' => $factura->proveedor->id,
                              'nombre' => $factura->proveedor->nombre,
                              'cif' => $factura->proveedor->cif,
                         ] : null,
                         'archivos' => json_decode($factura->imagen, true) ?? [],
                    ];
               })->toArray(),
               'archivosAñadidos' => $archivosAñadidos,
               'pdfsGenerados' => count($pdfsGenerados),
               'totalFacturas' => $facRecibidas->count(),
               'hasArchivos' => true,
               'message' => $mensaje
          ];
     }

     public function getStorageUrlsEnviadasRecibidasYTodas(Request $request)
     {
          $effectiveUserId = GestorHelper::getUserId($request);
          
          if (!$effectiveUserId) {
              return response()->json([
                  "facturas_enviadas" => "",
                  "facturas_recibidas" => "",
                  "url_base" => ""
              ], 200);
          }
          
          $directoryRelative = 'userId_' . $effectiveUserId;
          $url_base = Storage::url('public/lotes/' . $directoryRelative . '/');
          
          // Verificar si los archivos ZIP realmente existen antes de devolver sus URLs
          $facturas_enviadas_url = "";
          $facturas_recibidas_url = "";
          
          $enviadasPath = $directoryRelative . '/facturas_enviadas.zip';
          $enviadasExists = Storage::disk('lotes')->exists($enviadasPath);
          if ($enviadasExists) {
               $facturas_enviadas_url = $url_base . "facturas_enviadas.zip";
          }
          
          $recibidasPath = $directoryRelative . '/facturas_recibidas.zip';
          $recibidasExists = Storage::disk('lotes')->exists($recibidasPath);
          if ($recibidasExists) {
               $facturas_recibidas_url = $url_base . "facturas_recibidas.zip";
          }
          
          // Log para debugging
          Log::info('Verificación de URLs de ZIP', [
               'user_id' => $effectiveUserId,
               'enviadas_existe' => $enviadasExists,
               'enviadas_path' => $enviadasPath,
               'recibidas_existe' => $recibidasExists,
               'recibidas_path' => $recibidasPath,
               'directorio_completo' => storage_path('app/public/lotes/' . $directoryRelative)
          ]);
          
          return response()->json([
               "facturas_enviadas" => $facturas_enviadas_url,
               "facturas_recibidas" => $facturas_recibidas_url,
               "url_base" => $url_base
          ]);
     }
}
