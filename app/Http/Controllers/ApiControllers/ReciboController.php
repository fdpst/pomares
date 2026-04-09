<?php

namespace App\Http\Controllers\ApiControllers;

use App\Enums\ElectronicInvoiceType;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Traits\Basic;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Deuda;
use App\Models\AnioFiscal;
use App\Models\Recibo;
use App\Models\Cliente;
use App\Models\NroNota;
use App\Models\NroFactura;
use App\Models\NroFacturaRectificativa;
use App\Models\NroOrden;
use App\Models\NroFacturaProforma;
use App\Models\NroPresupuesto;
use App\Models\ReciboServicio;
use App\Models\NroParteTrabajo;
use App\Models\Albaranes\AlbaranesEnviado;
use App\Models\SystemParam;
use App\Enums\ParamSystemEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReciboRequest;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ParseHelper;
use App\Services\InvoiceToPDF;
use App\Services\ElectronicInvoicingService;
use App\Helpers\MessageHelper;
use App\Helpers\GestorHelper;

class ReciboController extends Controller
{
    /* tipo -> 'factura, presupuesto, nota, parte-trabajo'
     iva  -> true / false
  */

    protected $parseHelper;
    protected $messageHelper;

    public function __construct(ParseHelper $parseHelper, MessageHelper $messageHelper)
    {
        $this->parseHelper = $parseHelper;
        $this->messageHelper = $messageHelper;
    }

    // Funcion de obtener recibo por id
    public function getReciboById($recibo_id)
    {
        $recibo = Recibo::with([
            'servicios',
            'cliente:id,email',
            'nro_nota',
            'nro_factura',
            'nro_factura_rectificativa',
            'nro_orden',
            'nro_factura_prof',
            'nro_nota',
            'nro_presupuesto',
            'nro_parte_trabajo'
        ])->where('id', $recibo_id)->first();

        $recibo->makeVisible([
            'nro_factura',
            'nro_factura_rectificativa',
            'nro_orden',
            'nro_factura_prof',
            'nro_nota',
            'nro_presupuesto',
            'nro_parte_trabajo'
        ]);

        return response()->json($recibo, 200);
    }

    // Funcion de crear/actualizar recibo (factura, factura proforma, factura rectificativa, nota/albarán, presupuesto, parte de trabajo)
    public function saveRecibo(ReciboRequest $request, $tipo, $convertir_factura)
    {
        try {
            // Usar el helper para obtener el user_id correcto (cliente_id si es gestor)
            $effectiveUserId = GestorHelper::getUserId($request, $request->user_id);

            if (!$effectiveUserId) {
                return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
            }
            
            // Reemplazar el user_id del request con el effectiveUserId
            $request->merge(['user_id' => $effectiveUserId]);
            
            $metodo = $request->metodo;
            $nombre_archivo = null;
            $can_modify_document_number = $request->can_modify_document_number;
            $current_recibo = Recibo::find($request->id);

            DB::beginTransaction();

            if ($current_recibo) {
                $this->deleteFileByTipo($current_recibo, $tipo, $effectiveUserId);
            }

            // Parse dates before saving
            if ($request->has('fecha_tope')) {
                $request->merge(['fecha_tope' => Carbon::parse($request->fecha_tope)->format('Y-m-d')]);
            }
            if ($request->has('fecha')) {
                $request->merge(['fecha' => Carbon::parse($request->fecha)->format('Y-m-d')]);
            }
            if ($request->has('fecha_pago')) {
                $request->merge(['fecha_pago' => Carbon::parse($request->fecha_pago)->format('Y-m-d')]);
            }


            $recurrence = $request->recurrente ? $request->fecha_recurrente : null;

            if ($convertir_factura == 'true') {
                $request->merge([
                    'fecha' => date('Y-m-d'),
                    'fecha_recurrente' => $recurrence,
                ]);
                $recibo_duplicado = $current_recibo->replicate();
                $recibo = Recibo::updateOrCreate(
                    ['id' => $recibo_duplicado->id],
                    $request->except(['servicios', 'nro_presupuesto_id'])
                );
                $can_modify_document_number = false;
            } else {
                $recibo = Recibo::updateOrCreate(
                    ['id' => $request->id],
                    $request->merge([
                        'metodo_pago' => $metodo['field'] ?? null,
                        'detalle_pago' => $metodo['detalle'] ?? '',
                        'serie_id' => $request->serie_id,
                        'fecha_recurrente' => $recurrence,
                    ])
                        ->except(['servicios', 'nro_presupuesto_id'])
                );
            }

            $this->saveReciboServicios($recibo, $request->servicios, $request->user_id);

            /* comprobar el tipo */
            if ($tipo == 'presupuesto') {
                $nombre_archivo = $this->savePresupuesto($recibo, $tipo, $request->user_id);
                $recibo->presupuesto_url = $nombre_archivo;
            }

            if ($tipo == 'factura') {
                $nombre_archivo = $this->saveFactura($recibo, $tipo, $request->user_id, $request->albaranes, $request->checkbox, $metodo);
                $recibo->factura_url = $nombre_archivo;

                $orden_sepa =  $this->crearOrderSepa($request->cliente_id, $recibo, $request->user());
            }

            if ($tipo == 'facturarectificativa') {
                $nombre_archivo = $this->saveFacturaRectificativa($recibo, $tipo, $request->user_id, $request->albaranes, $request->checkbox, $metodo);
                $recibo->factura_url = $nombre_archivo;

                $orden_sepa =  $this->crearOrderSepa($request->cliente_id, $recibo, $request->user());
            }

            if ($tipo == 'facturaproforma') {
                $nombre_archivo = $this->saveFacturaProforma($recibo, $tipo, $request->user_id, $request->albaranes, $request->checkbox, $metodo);
                $recibo->factura_url = $nombre_archivo;
            }

            // Las notas son los albaranes
            if ($tipo == 'nota') {
                $nombre_archivo = $this->saveNota($recibo, $request->user_id);
                $recibo->nota_url = $nombre_archivo;
            }

            if ($tipo == 'parte-trabajo') {
                $this->asociarPresupuesto($recibo, $request->nro_presupuesto_id, $request->user_id);
            }

            if ($can_modify_document_number) {
                $this->updateReciboNumber($recibo, $request->type, $request->document_number);
            }

            $recibo->save();

            DB::commit();

            $recibo->load(
                'servicios',
                'cliente:id,email',
                'nro_factura',
                'nro_factura_rectificativa',
                'nro_orden',
                'nro_factura_prof',
                'nro_nota',
                'nro_presupuesto',
                'nro_parte_trabajo'
            );
            return response()->json([
                'recibo' => $recibo,
                'metodo' => $metodo['field'] ?? null,
                'message' => $this->messageHelper->success($tipo)
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error en saveRecibo: ' . $exception->getMessage());
            Log::error('Error en saveRecibo: ' . $exception->getLine());
            Log::error('Error en saveRecibo: ' . $exception->getFile());
            return response()->json([
                'error' => $exception->getMessage(),
                'message' => $this->messageHelper->error($tipo)
            ], 400);
        }
    }
    public function saveReciboServicios(Recibo $recibo, $servicios, $userId)
    {
        $ids = [];
        foreach ($servicios as $key => $servicio) {
            $reciboServicio = ReciboServicio::updateOrCreate(
                [
                    'id' => $servicio['id'],
                    'recibo_id' => $recibo->id
                ],
                [
                    'user_id' => $userId,
                    'recibo_id' => $recibo->id,
                    'descripcion' => $servicio['descripcion'],
                    'id_servicio' => $servicio['id_servicio'] ?? null,
                    'cantidad' => intval($servicio['cantidad']),
                    'precio' => $this->parseHelper->parseEuroNumber($servicio['precio']),
                    'importe' => $this->parseHelper->parseEuroNumber($servicio['importe']),
                    'iva_percent' => $this->parseHelper->parseEuroNumber($servicio['iva_percent'] ?? 0),
                    'importe_iva' => $this->parseHelper->parseEuroNumber($servicio['importe_iva']),
                    'lote' => $servicio['lote'] ?? null,
                ]
            );

            $ids[] = $reciboServicio->id;
        }

        // Eliminamos los servicios que no están en el array
        ReciboServicio::where('recibo_id', $recibo->id)->whereNotIn('id', $ids)->delete();
    }

    // Funciones auxiliares que guardan el nro de recibo (según correspondencia); crean la deuda y generan el pdf
    public function saveFactura(Recibo $recibo, $tipo, $userId, $albaranes, $checkbox, $metodo)
    {
        $current_deuda = null;

        $res = $this->setNroRecibo($recibo, NroFactura::class, 'nro_factura', $userId);
        $nro_factura = $res['model'];
        $nroFactura = $res['nroRecibo'];

        /*Comprobar si existe la relacion*/
        $current_deuda = $nro_factura->deuda;
        if ($current_deuda) {
            $current_deuda->deuda_id = $nro_factura['id'];
            $current_deuda->total = $recibo->total;
            $current_deuda->save();
        } else {
            $nro_factura->deuda()->create([
                'total' => $recibo->total,
                'user_id' => $userId,
                'nro_factura' => $nroFactura
            ]);
        }

        // contabilizar albaranes con numero factura creada
        foreach ($checkbox as $chbx) {
            if ($chbx != null) {
                $nombre = "Factura " . $nroFactura;
                $contabilizado = DB::table('albaranes_enviados')
                    ->where(['nro_factura' => $chbx])
                    ->update(array('contabilizado' => $nombre));
            }
        }

        // Primero comprobamos si el usuario efctivo (es decir la empresa) tiene facturacion electronica
        // Si no, comprobamos si el usuario autenticado tiene facturacion electronica
        // Si no, se establece como false
        $hasElectronicBilling = User::find($userId)->has_electronic_billing ?? Auth::user()?->has_electronic_billing ?? false;
        if ($hasElectronicBilling) {
            $resp = ElectronicInvoicingService::getInstance()
                ->getReciboToSend($recibo->id, ElectronicInvoiceType::F1)
                ->send();

            $qr = $resp['qr'] ?? '';

            $binaryData = base64_decode($qr);

            // Crear un objeto finfo para detectar el MIME type
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($binaryData);

            $recibo->qr_code_electronic_invoice = isset($resp['qr']) && $resp['qr'] != "" ? ('data:' . $mimeType . ';base64,' . $qr) : "";
            $recibo->qr_code_electronic_invoicing_string = $resp['uuid'] ?? '';
            $recibo->save();
            Log::debug(json_encode($resp) . " respuesta api facturacion electronica");
        }
        // Almacenamos el PDF
        $nombre_archivo = $this->savePdf_AUX($recibo->load('cliente', 'servicios', 'servicios.Servicio', 'cliente.provincia'), $nroFactura, 'factura', $userId, $albaranes, $metodo);

        /// 18/07/25 Comentar temporalmente hasta que no se realice un microservicio en un VPS para usar la libreria browsershot
        /*$binaryPdf = InvoiceToPDF::getInstance()->getPdfForTypeAndInvoice($recibo->id);
        $nombre_archivo = InvoiceToPDF::getDocumentNameByType(
            InvoiceToPDF::INVOICE,
            $nroFactura
        );
        Storage::disk('recibos')->put('userId_' . $userId . '/' . $nombre_archivo, $binaryPdf);
        $this->saveFilesForDocumentsApp(
            $nombre_archivo,
            $recibo,
            $nroFactura,
            $tipo,
            $userId,
            $binaryPdf
        );*/
        return $nombre_archivo;
    }
    public function saveFacturaRectificativa(Recibo $recibo, $tipo, $userId, $albaranes, $checkbox, $metodo)
    {
        $current_deuda = null;

        $res = $this->setNroRecibo($recibo, NroFacturaRectificativa::class, 'nro_factura', $userId);
        $nro_factura = $res['model'];
        $nroFactura = $res['nroRecibo'];

        /*Comprobar si existe la relacion*/
        $current_deuda = $nro_factura->deuda;
        if ($current_deuda) {
            $current_deuda->deuda_id = $nro_factura['id'];
            $current_deuda->total = $recibo->total;
            $current_deuda->save();
        } else {
            $nro_factura->deuda()->create([
                'total' => $recibo->total,
                'user_id' => $userId,
                'nro_factura' => $nroFactura
            ]);
        }

        // contabilizar albaranes con numero factura creada
        foreach ($checkbox as $chbx) {
            if ($chbx != null) {
                $nombre = "Factura " . $nroFactura;
                $contabilizado = DB::table('albaranes_enviados')
                    ->where(['nro_factura' => $chbx])
                    ->update(array('contabilizado' => $nombre));
            }
        }
        if (Auth::user()->has_electronic_billing) {
            $resp = ElectronicInvoicingService::getInstance()
                ->getReciboToSend($recibo->id, ElectronicInvoiceType::R1)
                ->send();
            $qr = $resp['qr'] ?? '';
            Log::debug("Respuesta verifactu: " . json_encode($resp));

            $binaryData = base64_decode($qr);

            // Crear un objeto finfo para detectar el MIME type
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($binaryData);

            $recibo->qr_code_electronic_invoice = isset($resp['qr']) && $resp['qr'] != "" ? ('data:' . $mimeType . ';base64,' . $qr) : "";
            $recibo->qr_code_electronic_invoicing_string = $resp['uuid'] ?? '';
            $recibo->save();
            Log::debug(json_encode($resp) . " respuesta api facturacion electronica");
        }
        // Almacenamos el PDF
        $nombre_archivo = $this->savePdf_AUX($recibo->load('cliente', 'servicios', 'servicios.Servicio', 'cliente.provincia'), $nroFactura, 'facturarectificativa', $userId, $albaranes, $metodo);

        /// 18/07/25 Comentar temporalmente hasta que no se realice un microservicio en un VPS para usar la libreria browsershot
        /*$binaryPdf = InvoiceToPDF::getInstance()->getPdfForTypeAndInvoice($recibo->id);
        $nombre_archivo = InvoiceToPDF::getDocumentNameByType(
            InvoiceToPDF::INVOICE_RECTIFICATIVA,
            $nroFactura
        );
        Storage::disk('recibos')->put('userId_' . $userId . '/' . $nombre_archivo, $binaryPdf);
        $this->saveFilesForDocumentsApp(
            $nombre_archivo,
            $recibo,
            $nroFactura,
            $tipo,
            $userId,
            $binaryPdf
        );*/
        return $nombre_archivo;
    }
    private function saveFacturaProforma(Recibo $recibo, $tipo, $userId, $albaranes, $checkbox, $metodo)
    {
        $res = $this->setNroRecibo($recibo, NroFacturaProforma::class, 'nro_factura_prof', $userId);
        $nro_factura_prof = $res['model'];
        $nroFacturaProf = $res['nroRecibo'];

        // Almacenamos el PDF
        $nombre_archivo = $this->savePdf_AUX_prof($recibo->load('cliente', 'servicios', 'servicios.Servicio', 'cliente.provincia'), $nroFacturaProf, 'facturaproforma', $userId, $albaranes, $metodo);

        /// 18/07/25 Comentar temporalmente hasta que no se realice un microservicio en un VPS para usar la libreria browsershot
        /*$binaryPdf = InvoiceToPDF::getInstance()->getPdfForTypeAndInvoice($recibo->id);
        $nombre_archivo = InvoiceToPDF::getDocumentNameByType(
            InvoiceToPDF::INVOICE_PROFORMA,
            $nroFacturaProf
        );
        Storage::disk('recibos')->put('userId_' . $userId . '/' . $nombre_archivo, $binaryPdf);
        $this->saveFilesForDocumentsApp(
            $nombre_archivo,
            $recibo,
            $nroFacturaProf,
            $tipo,
            $userId,
            $binaryPdf
        );*/
        return $nombre_archivo;
    }
    private function savePresupuesto(Recibo $recibo, $tipo, $userId)
    {
        $anio = AnioFiscal::orderBy('year', 'Desc')->first();
        $nro_presupuesto = NroPresupuesto::updateOrCreate(
            ['recibo_id' => $recibo->id],
            [
                'recibo_id' => $recibo->id,
                'user_id' => $userId,
                'recibo_id' => $recibo->id,
                'id_anio' => $anio->id
            ]
        );
        $nombre_archivo = $this->savePdf($recibo->load('cliente', 'servicios', 'servicios.Servicio', 'cliente.provincia'), $nro_presupuesto->nro_presupuesto, 'presupuesto', $userId);
        return $nombre_archivo;
    }
    // recordar que las notas ahora son los albaranes
    private function saveNota(Recibo $recibo, $userId)
    {
        $current_deuda = null;
        // # START asigna numero de nota correlativo segun usuario id
        // #  cambiado oscar para numeracion correcta
        // nota
        // anterior
        // $nota = NroNota::where('user_id', Auth::user()->id)->where('recibo_id', $recibo->id)->first();
        // Nuevo 07/10/22
        $nota = NroNota::where('user_id', $userId)->where('recibo_id', $recibo->id)->orderBy('nro_nota', 'desc')->first();
        if ($nota) {
            $nroNota = Basic::completarConCero($nota->nro_nota, 4);
        } else {
            // $nota =  NroNota::where(['user_id' => Auth::user()->id])->get();
            // $valorNota = (1*count($nota) + 1*1);
            // $nroNota =  Basic::completarConCero($valorNota, 4);

            // Nuevo 07/10/22
            $nota =  NroNota::where(['user_id' => $userId])->orderBy('id', 'desc')->first();
            if ($nota == null) {
                $valorNota = 1;
                $nroNota =  Basic::completarConCero($valorNota, 4);
            } else {
                $valorNota = ($nota->nro_nota + 1);
                $nroNota =  Basic::completarConCero($valorNota, 4);
            }
        }

        // # END asigna numero de nota correlativo segun usuario id
        $anio = AnioFiscal::orderBy('year', 'Desc')->first();
        $nro_nota = NroNota::updateOrCreate(['recibo_id' => $recibo->id], ['user_id' => $userId], ['recibo_id' => $recibo->id], ['id_anio' => $anio->id]);
        $current_deuda = $nro_nota->deuda;
        /*Comprobar si existe la relacion*/
        if ($current_deuda) {
            $current_deuda->deuda_id = $nro_nota['id'];
            $current_deuda->total = $recibo->total;
            $current_deuda->save();
        } else {
            $nro_nota->deuda()->create(
                [
                    'total'    => $recibo->total,
                    'user_id'  => $userId,
                    'nro_nota' => $nroNota
                ]
            );
        }
        $nombre_archivo = $this->savePdf($recibo->load('cliente', 'servicios', 'servicios.Servicio', 'cliente.provincia'), $nroNota, 'nota', $userId);
        return $nombre_archivo;
    }

    /**
     * Set the number of the receipt
     * 
     * @param Recibo $recibo
     * @param string $model
     * @param string $field
     * @param int $userId
     * @return array
     */
    private function setNroRecibo(Recibo $recibo, $model, string $field, int $userId)
    {
        $anio = AnioFiscal::orderBy('year', 'DESC')->first();

        // Buscar si ya existe un registro para este recibo
        $existingRecord = $model::where('recibo_id', $recibo->id)->first();

        // Si existe el numero de recibo, se obtiene el numero de recibo
        if ($existingRecord != null) {
            $nroReciboInstance = $model::where(['user_id' => $userId])
                ->where(['recibo_id' => $recibo->id]);

            // Si existe la serie, se filtra por la serie del numero de recibo
            $nroReciboInstance->whereHas('recibo', function ($query) use ($recibo) {
                $query->where('serie_id', $recibo->serie_id);
            });

            // Se obtiene el numero de recibo
            $nroReciboInstance = $nroReciboInstance->first();

            // Se completa el numero de recibo con ceros
            $nroRecibo = Basic::completarConCero($nroReciboInstance[$field], 4);
        } else {
            // Si no existe el numero de recibo, se obtiene el numero de recibo de la ultima factura
            $lastRecord =  $model::where(['user_id' => $userId])
                ->where('id_anio', $anio->id);

            // Siempre filtrar por serie: si tiene serie, filtrar por esa serie; si no tiene serie, filtrar por null
            $lastRecord->whereHas('recibo', function ($query) use ($recibo) {
                $query->where('serie_id', $recibo->serie_id);
            });

            $lastRecord = $lastRecord->orderBy($field, 'DESC')->first();

            // Si no existe el numero de recibo, se establece el numero de recibo a 1
            if ($lastRecord == null) {
                $valorFactura = 1;
                $nroRecibo = Basic::completarConCero($valorFactura, 4);
            } else {
                // Si existe el numero de recibo, se incrementa el numero de recibo
                $valorFactura = ($lastRecord[$field] + 1);
                $nroRecibo = Basic::completarConCero($valorFactura, 4);
            }
        }

        // Se actualiza el numero de recibo
        $nroReciboUpdated = $model::updateOrCreate(
            ['recibo_id' => $recibo->id],
            [
                'recibo_id' => $recibo->id,
                'user_id' => $userId,
                $field => $nroRecibo,
                'id_anio' => $anio->id
            ]
        );

        return ['model' => $nroReciboUpdated, 'nroRecibo' => $nroRecibo];
    }

    /**
     * Update the number of the receipt
     * 
     * @param Recibo $recibo
     * @param string $type
     * @param int|null $new_number
     * @return bool
     */
    private function updateReciboNumber(Recibo $recibo, string $type, int|null $new_number = null)
    {
        $mapping = [
            [
                'type' => 'factura',
                'model' => NroFactura::class,
                'field' => 'nro_factura',
                'filename' => 'factura_url',
                'nombre' => 'factura'
            ],
            [
                'type' => 'facturarectificativa',
                'model' => NroFacturaRectificativa::class,
                'field' => 'nro_factura',
                'filename' => 'factura_url',
                'nombre' => 'factura rectificativa'
            ],
            [
                'type' => 'presupuesto',
                'model' => NroPresupuesto::class,
                'field' => 'nro_presupuesto',
                'filename' => 'presupuesto_url',
                'nombre' => 'presupuesto'
            ],
            [
                'type' => 'facturaproforma',
                'model' => NroFacturaProforma::class,
                'field' => 'nro_factura_prof',
                'filename' => 'factura_url',
                'nombre' => 'factura proforma'
            ],
            [
                'type' => 'nota',
                'model' => NroNota::class,
                'field' => 'nro_nota',
                'filename' => 'nota_url',
                'nombre' => 'albarán'
            ],
            [
                'type' => 'parte-trabajo',
                'model' => NroParteTrabajo::class,
                'field' => 'nro_parte_trabajo',
                'filename' => 'orden_url',
                'nombre' => 'parte de trabajo'
            ]
        ];

        $mapped = collect($mapping)->firstWhere('type', $type);

        if (!$mapped) {
            throw new \Exception($this->messageHelper->error('invalid-document-type'), 400);
        }

        $invoice_number = $this->checkNumberExists(
            $mapped['model'],
            $mapped['field'],
            $recibo->id,
            $new_number,
            $recibo->serie_id,
            $mapped['nombre']
        );

        $invoice_number[$mapped['field']] = $new_number ?? $invoice_number[$mapped['field']];
        $invoice_number->save();

        // $filename = $this->savePdf($recibo, $invoice_number[$mapped['field']], $mapped['nombre'], Auth::user()->id);
        // $recibo->update([$mapped['filename'] => $filename]);
        return true;
    }

    /**
     * Check if the number exists in the database
     * @param Model $model
     * @param string $field
     * @param int $reciboId
     * @param int|null $number
     * @param int|null $serieId
     * @return Model
     */
    private function checkNumberExists($model, string $field, int $reciboId, int|null $number = null, int|null $serieId = null, string $type)
    {
        // Obtener el primer numero de recibo segun el recibo id
        $old_invoice_number = $model::where("recibo_id", $reciboId);

        if (isset($serieId)) {
            $old_invoice_number->whereHas('recibo', function ($query) use ($serieId) {
                $query->where('serie_id', $serieId);
            });
        }
        $old_invoice_number = $old_invoice_number->first();

        // Si se está proporcionando un nuevo número, validar que no exista en otro recibo
        if (!is_null($number)) {
            // Buscar si existe el numero en otro recibo
            $exists_new = $model::where($field, $number)
                ->where("recibo_id", "!=", $reciboId)
                ->where('user_id', Auth::user()->id);

            if (isset($serieId)) {
                $exists_new->whereHas('recibo', function ($query) use ($serieId) {
                    $query->where('serie_id', $serieId);
                });
            }

            $exists_new = $exists_new->first();

            // Si existe el numero en otro recibo, lanzar error
            if (!is_null($exists_new)) {
                throw new \Exception($this->messageHelper->error('number-exists', ['attribute' => $type]), 400);
            }
        }

        return $old_invoice_number;
    }

    // Funciones auxiliares que generan el pdf según el tipo de documento
    public function savePdf(Recibo $recibo, $nro_factura, $tipo, $userId)
    {
        try {
            // $view = $tipo == 'nota' ? 'pdf.nota' : 'pdf.recibo';
            $view = 'pdf.new-recibo';

            $user = User::where('id', $userId)->with(['provincia', 'metodos_pago'])->first();
            // Usar ruta directa del archivo en lugar de base64 para evitar problemas de memoria
            $user->logo = $this->parseHelper->getLogoPath($user->avatar ?? '');

            $showLote = $this->batchModeEnabledFor($userId);

            $pdf = PDF::loadView($view, [
                'recibo'       => $recibo,
                'fecha'        => Carbon::parse($recibo->fecha)->format('d-m-Y'),
                'userLog'      => $user,
                'nro_factura'  => $nro_factura,
                'tipo'         => $tipo,
                'show_lote'    => $showLote,
            ]);

            $nro = str_pad($nro_factura, 4, '0', STR_PAD_LEFT);

            $nombre_archivo = Carbon::now()->valueof() . '_' .  strtoupper($tipo) . '_' . $nro . '.pdf';

            // Generar el PDF una sola vez y reutilizar el resultado
            $pdfOutput = $pdf->output();

            Storage::disk('recibos')->put('userId_' . $userId . '/' . $nombre_archivo, $pdfOutput);

            $archivosGestorDocumental = $this->saveFilesForDocumentsApp($nombre_archivo, $recibo, $nro_factura, $tipo, $userId, $pdfOutput);

            // Liberar memoria
            unset($pdfOutput);
            
            return $nombre_archivo;
        } catch (\Exception $ex) {
            // Log del error para debugging
            Log::error('Error en savePdf: ' . $ex->getMessage());
            Log::error('Stack trace: ' . $ex->getTraceAsString());
            throw $ex;
        }
    }
    public function savePdf_AUX(Recibo $recibo, $nro_factura, $tipo, $userId, $albaranes, $metodo)
    {
        try {
            // $view = $tipo == 'nota' ? 'pdf.nota' : (($tipo  == 'factura' || $tipo == 'facturarectificativa') ? 'pdf.factura' : 'pdf.recibo');
            $view = 'pdf.new-recibo';

            $user = User::where('id', $userId)->with(['provincia', 'metodos_pago'])->first();
            // Usar ruta directa del archivo en lugar de base64 para evitar problemas de memoria
            $user->logo = $this->parseHelper->getLogoPath($user->avatar ?? '');

            $totalImporte = 0;

            foreach ($albaranes as $albaran) {
                $totalImporte = 1 * ($totalImporte + $albaran['importe']);
            }

            $showLote = $this->batchModeEnabledFor($userId);

            $pdf = PDF::loadView($view, [
                'recibo'              => $recibo,
                'fecha'               => Carbon::parse($recibo->fecha)->format('d-m-Y'),
                'userLog'             => $user,
                'nro_factura'         => $nro_factura,
                'tipo'                => $tipo,
                'albaranes'           => $albaranes,
                'totalImporteAlbaran' => $totalImporte,
                'metodo'              => $metodo,
                'show_lote'           => $showLote,
            ]);

            $nro = str_pad($nro_factura, 4, '0', STR_PAD_LEFT);

            $nombre_archivo = Carbon::now()->valueof() . '_' .  strtoupper($tipo) . '_' . $nro . '.pdf';
    
            // Generar el PDF una sola vez y reutilizar el resultado
            $pdfOutput = $pdf->output();

            Storage::disk('recibos')->put('userId_' . $userId . '/' . $nombre_archivo, $pdfOutput);

            //Determinar archivos para gestor documental
            $archivosGestorDocumental = $this->saveFilesForDocumentsApp($nombre_archivo, $recibo, $nro_factura, $tipo, $userId, $pdfOutput);
            
            // Liberar memoria
            unset($pdfOutput);

            return $nombre_archivo;
        } catch (\Exception $ex) {
            Log::error('Error en savePdf_AUX: ' . $ex->getMessage());
            Log::error('Stack trace: ' . $ex->getTraceAsString());
            throw $ex;
        }
    }
    public function savePdf_AUX_prof(Recibo $recibo, $nroFacturaProf, $tipo, $userId, $albaranes, $metodo)
    {
        try {
            // $view = $tipo == 'nota' ? 'pdf.nota' : 'pdf.recibo';
            $view = 'pdf.new-recibo';

            $user = User::where('id', $userId)->with(['provincia', 'metodos_pago'])->first();
            // Usar ruta directa del archivo en lugar de base64 para evitar problemas de memoria
            $user->logo = $this->parseHelper->getLogoPath($user->avatar ?? '');

            $totalImporte = 0;
            foreach ($albaranes as $albaran) {
                $totalImporte = 1 * ($totalImporte + $albaran['importe']);
            }
            $showLote = $this->batchModeEnabledFor($userId);

            $pdf = PDF::loadView($view, [
                'recibo'              => $recibo,
                'fecha'               => Carbon::parse($recibo->fecha)->format('d-m-Y'),
                'userLog'             => $user,
                'nro_factura'         => $nroFacturaProf,
                'tipo'                => $tipo,
                'albaranes'           => $albaranes,
                'totalImporteAlbaran' => $totalImporte,
                'metodo'              => $metodo,
                'show_lote'           => $showLote,
            ]);

            $nro = str_pad($nroFacturaProf, 4, '0', STR_PAD_LEFT);

            $nombre_archivo = Carbon::now()->valueof() . '_' .  strtoupper($tipo) . '_' . $nro . '.pdf';

            // Generar el PDF una sola vez y reutilizar el resultado
            $pdfOutput = $pdf->output();

            Storage::disk('recibos')->put('userId_' . $userId . '/' . $nombre_archivo, $pdfOutput);

            $archivosGestorDocumental = $this->saveFilesForDocumentsApp($nombre_archivo, $recibo, $nroFacturaProf, $tipo, $userId, $pdfOutput);

            // Liberar memoria
            unset($pdfOutput);

            return $nombre_archivo;
        } catch (\Exception $ex) {
            Log::error('Error en savePdf_AUX_prof: ' . $ex->getMessage());
            Log::error('Stack trace: ' . $ex->getTraceAsString());
            throw $ex;
        }
    }

    // Funcion de crear orden sepa - LLamada en saveRecibo
    private function crearOrderSepa($cliente_id, Recibo $recibo, $user)
    {
        $cliente = Cliente::with('provincia')->where('id', $cliente_id)->get()->first();

        $user_id = auth()->user()->id;

        $nro_orden = NroOrden::updateOrCreate(['recibo_id' => $recibo['id'], 'user_id' => $user_id], [
            'recibo_id' => $recibo['id'],
            'user_id'   => $user_id
        ]);

        $nro = str_pad($nro_orden->nro_orden, 4, '0', STR_PAD_LEFT);

        $pdf = PDF::loadView('pdf.orden.orden_sepa', [
            'cliente' => $cliente,
            'nro'     => $nro,
            'user' => $user
        ]);

        $nombre_archivo = Carbon::now()->valueof() . '_' . 'ORDEN' . '_' . $nro . '.pdf';

        Storage::disk('recibos')->put("userId_{$user_id}/{$nombre_archivo}", $pdf->output());

        $recibo->update(['orden_url' => $nombre_archivo]);

        //$file_path = Storage::disk('recibos')->path('userId_'.$userId.'/'.$nombre_archivo);
        //Determinar archivos para gestor documental
        //$archivosGestorDocumental = $this->saveFilesForDocumentsApp($nombre_archivo, $recibo, $nro_factura, $tipo, $userId, $pdf->output());
        return ['status' => 'success'];
    }

    // Funcion de asociar presupuesto a parte de trabajo - LLamada en saveRecibo
    private function asociarPresupuesto(Recibo $recibo, $nro_presupuesto_id, $userId)
    {
        // # START asigna numero de parte-trabajo correlativo segun usuario id
        // #  cambiado oscar para numeracion correcta
        // partetrabajo
        $partetrabajo =  NroParteTrabajo::where(['user_id' => Auth::user()->id])->get();
        $valorPartetrabajo = (1 * count($partetrabajo) + 1 * 1);
        $nroPartetrabajo =  Basic::completarConCero($valorPartetrabajo, 4);
        // # END asigna numero de parte-trabajo correlativo segun usuario id

        $saved_nro_parte_trabajo = NroParteTrabajo::where('recibo_id', $recibo->id)->get()->first();
        if ($saved_nro_parte_trabajo) {
            $saved_nro_parte_trabajo->delete();
        }
        return $nro_parte_trabajo = NroParteTrabajo::updateOrCreate(
            [
                'recibo_id' => $recibo->id,
                'user_id' => $userId,
                'nro_presupuesto_id' => $nro_presupuesto_id,
                'nro_parte_trabajo' => $nroPartetrabajo
            ]
        );
    }

    // Funcion de obtener albaran enviado por nro de factura
    public function getReciboByName($elemento)
    {
        $recibo = AlbaranesEnviado::with('cliente:id,email')->where('nro_factura', $elemento)->get()->first();
        return response()->json($recibo, 200);
    }

    // Funcion de cambiar el año fiscal
    public function CambiarAnioFiscal()
    {
        $anio = AnioFiscal::orderBy('year', 'Desc')->first();
        AnioFiscal::create(['year' => ($anio->year + 1)]);
    }

    // Funciones auxiliares que guardan/eliminan los archivos en el gestor documental
    public function saveFilesForDocumentsApp($nombre_archivo, $recibo, $nro_factura, $tipo, $userId, $pdf)
    {
        # Salvar archivos en los directorios correspondientes al usuario
        $path = 'userId_' . $userId;
        // Storage::makeDirectory($path);
        $putDocument =  Storage::disk('documentos')->put($path . '/' . $tipo . '/' . $nombre_archivo, $pdf);
    }
    private function deleteFileByTipo(Recibo $current_recibo, $tipo, $userId)
    {
        $nombre_pdf = null;

        if ($tipo == 'presupuesto') {
            $nombre_pdf = $current_recibo->presupuesto_url;
        }

        if ($tipo == 'factura' || $tipo == 'facturarectificativa' || $tipo == 'facturaproforma') {
            $nombre_pdf = $current_recibo->factura_url;
        }

        if ($tipo == 'nota') {
            $nombre_pdf = $current_recibo->nota_url;
        }

        return $this->deleteFileIfExists('recibos', 'userId_' . $userId . '/' . $nombre_pdf);
    }
    private function deleteFileIfExists($nombre_disco, $nombre_archivo)
    {
        $nombre_archivo = !empty($nombre_archivo) ? $nombre_archivo : '';

        if (Storage::disk($nombre_disco)->exists($nombre_archivo)) {
            Storage::disk($nombre_disco)->delete($nombre_archivo);
        }
    }

    // Funcion de subir imagen para el editor de pdf
    public function uploadImageEditor(Request $request)
    {
        if ($request->file('image')) {
            $url = $request->file('image')->store('images', 'public');

            return response()->json([
                'url' => env('VITE_API_BASE_URL') . 'storage/' . $url
            ], 200);
        }

        return response()->json([
            'error' => 'No se pudo subir la imagen.'
        ], 500);
    }

    public function removeContabilizado($elemento, $idServicio, $idRecibo)
    {
        // Comprobamos si es un albaran y eliminamos el contabilizadoa
        $elementNro = strval(substr($elemento, -4));

        $idAlb = AlbaranesEnviado::where('nro_factura', $elementNro)->get()->first();
        $idAlbaran = $idAlb->id;

        $contabilizado = AlbaranesEnviado::findOrFail($idAlbaran);
        $contabilizado->contabilizado =  null;
        $contabilizado->update();

        // Buscamos el Servicio y guardamos la id del recibo y la del usuario para su posterior uso
        $existeRecibo = ReciboServicio::where('id', $idServicio)->get()->first();
        if ($existeRecibo) {
            //$reciboServicio = ReciboServicio::where('id', $idServicio)->get()->first();
            $reciboServId = $existeRecibo->recibo_id;
            $reciboUserId = $existeRecibo->user_id;
            $existeRecibo->delete();
        }

        // Si no queda ningun servicio en el listado almacenamos una linea ficticia para poder guardar la factura ### falla desde los cambios del viernes
        $vacio = ReciboServicio::where('recibo_id', $idRecibo)->get()->first();

        if (!$vacio) {

            $reciboServicio = ReciboServicio::create([
                'user_id' => $reciboUserId,

                'recibo_id' => $idRecibo,
                'descripcion' => 'Descripción',
                'cantidad' => 0,
                'precio' => 0,
                'importe' => 0
            ]);
            // Devolvemos el servicio creado para mostrar en la lista
            return $reciboServicio;
        }
        return null;
    }

    protected function batchModeEnabledFor($userId): bool
    {
        static $cache = [];

        if (array_key_exists($userId, $cache)) {
            return $cache[$userId];
        }

        $value = SystemParam::where('business_id', $userId)
            ->where('param', ParamSystemEnum::ENABLE_BATCH->value)
            ->value('value');

        $cache[$userId] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;

        return $cache[$userId];
    }

    // Funcion de preview del pdf para maquetado
    public function previewPdf(Request $request)
    {
        try {
            $tipo = 'factura';
            $view = $tipo == 'nota' ?
                'pdf.nota' : (
                    ($tipo  == 'factura' || $tipo == 'facturarectificativa') ?
                    'pdf.new-recibo' :
                    'pdf.recibo'
                );

            $recibo = Recibo::with(['nro_factura.Anio'])->find(1);
            $nro_factura = NroFactura::with(['Anio'])->where('recibo_id', $recibo->id)->first();

            $user = User::where('id', $recibo->user_id)
                ->with(['provincia', 'metodos_pago'])
                ->first();
                
            $totalImporte = 0;
            $albaranes = [];
            foreach ($albaranes as $albaran) {
                $totalImporte = 1 * ($totalImporte + $albaran['importe']);
            }

            $user->logo = $this->parseHelper->getLogoBase64($user->avatar ?? '');

            $pdf = [
                'recibo'              => $recibo,
                'fecha'               => Carbon::parse($recibo->fecha)->format('d-m-Y'),
                'userLog'             => $user,
                'nro_factura'         => $nro_factura->nro_factura,
                'tipo'                => $tipo,
                'albaranes'           => $albaranes,
                'totalImporteAlbaran' => $totalImporte,
                'metodo'              => $metodo ?? null
            ];

            // return $pdf['userLog'];

            return view($view, $pdf);
        } catch (\Exception $ex) {
            dd($ex);
        }
    }
}
