<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Traits\Basic;
use App\Traits\Files\HandlerFiles;
use App\Http\Requests\AlbaranRequest;
use App\Http\Resources\AlbaranResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\AnioFiscal;
use App\Models\User;
use App\Models\Recibo;
use App\Models\NroNota;
use App\Models\Cliente;
use App\Models\Albaran;
use App\Models\Proveedor;
use App\Models\NroFactura;
use App\Models\ReciboServicio;
use App\Models\AlbaranEnviadoItemAgregado;
use App\Models\Albaranes\AlbaranesEnviado;
use App\Helpers\ParseHelper;
use App\Models\SystemParam;
use App\Enums\ParamSystemEnum;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AlbaranController extends Controller
{
  protected $parseHelper;
  public function __construct(ParseHelper $parseHelper)
  {
    $this->parseHelper = $parseHelper;
  }

  public function getAlbaranes($user_id){
    $albaranes = AlbaranResource::collection(Albaran::orderBy('created_at', 'DESC')->where('user_id', '=', $user_id)->get());
    $proveedores = Proveedor::where('user_id', '=', $user_id)->orderBy('created_at', 'DESC')->get();
    return response()->json(['status' => 200,'albaranes' => $albaranes,'proveedores' => $proveedores]);
  }

  public function getAlbaranById($albaran_id){
    $albaran = new AlbaranResource(Albaran::where('id', $albaran_id)->get()->first());
    return response()->json(['status' => 200,'albaran' => $albaran]);
  }

  protected function pathServer(){
      $PATH = $_SERVER['DOCUMENT_ROOT'];
      $pathPublicOut = explode('public',$PATH);
      $res = $pathPublicOut[0]; 
      return $res;
  }

  public function saveAlbaran(Request $request){    
    $albaran = new Albaran;
    $albaran->user_id = $request->user_id;
    $albaran->proveedor_id = $request->proveedor_id;
    $albaran->descripcion = $request->descripcion;
    $albaran->fecha = $request->fecha;
    $albaran->save();
    // $destination  = $this->pathServer() . "/storage/app/public/albaranes/recibidos/";
    // $storeFiles = HandlerFiles::store($request, $destination);
    // $storeFiles->original['nombresArchivos'];
    $destination  = $this->pathServer() . "/storage/app/public/documentos/userId_". $request->user_id . "/factura_recibidas/";
    $storeFiles = HandlerFiles::store($request, $destination);
    $storeFiles->original['nombresArchivos'];
    if (count($storeFiles->original['nombresArchivos']) > 0) {
      $n = Albaran::findOrFail($albaran->id);
      $n->pdf = $storeFiles->original['nombresArchivos'];
      $n->update();
    }
    return response()->json($albaran, 200);
  }

  public function updatelbaran(AlbaranRequest $request, $id){
    $albaran = Albaran::findOrFail($id);
    $albaran->user_id = $request->user_id;
    $albaran->proveedor_id = $request->proveedor_id;
    $albaran->descripcion = $request->descripcion;
    $albaran->fecha = $request->fecha;
    $albaran->update();
    // $destination  = $this->pathServer() . "storage/app/public/albaranes/recibidos/";
    // $storeFiles = HandlerFiles::store($request, $destination);
    // $storeFiles->original['nombresArchivos'];
    $destination  = $this->pathServer() . "/storage/app/public/documentos/userId_". $request->user_id ."/factura_recibidas/";
    $storeFiles = HandlerFiles::store($request, $destination);
    $storeFiles->original['nombresArchivos'];     
    if (count($storeFiles->original['nombresArchivos']) > 0) {
      $n = Albaran::findOrFail($albaran->id);
      $n->pdf = json_encode($storeFiles->original['nombresArchivos']);
      $n->update();
    }
    return response()->json($albaran, 200);
  }

  public function pdfAPARTE($request){
       return  Storage::disk('albaranes')->put('xxx', $request->imagen);
  }  

  public function deleteAlbaran($albaran_id){
    $albaran = Albaran::find($albaran_id);
    if(Storage::disk('albaranes')->exists($albaran->imagen)){
       Storage::disk('albaranes')->delete($albaran->imagen);
    }
    $albaran->delete();
    return response()->json('Albaran eliminado', 200);
  }

  public function getnviados(Request $request, $user_id = null){
    // Usar el helper para obtener el user_id correcto (cliente_id si es gestor)
    $effectiveUserId = \App\Helpers\GestorHelper::getUserId($request, $user_id);
    
    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }
    
    $enviados = AlbaranesEnviado::with('cliente')->orderBy('created_at', 'DESC')->where('user_id', '=', $effectiveUserId)->get();     
    return response()->json(['status' => 200,'enviados' => $enviados]);
  }

  // Crear albaran enviado
  public function albaranesEnviadosF(Request $request){ 
    try{
      // Usar GestorHelper para obtener el user_id correcto
      $userLoggedId = \App\Helpers\GestorHelper::getUserId($request, Auth::id());
      if (!$userLoggedId) {
        return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
      }
      
      $user = User::where('id', $userLoggedId)->with('provincia')->first();

      $data =  json_decode($request->enviados);

      $total = 0;
      $cantidad = 0;
      foreach ($data as $value) {
        $total = (1*$total + 1*$this->parseHelper->parseEuroNumber($value->importe));
        $cantidad = $cantidad + intval($value->cantidad);
      }

      $anio = AnioFiscal::latest()->first();

      $albaranes = AlbaranesEnviado::where(['user_id' => $userLoggedId])
        ->where('id_anio',$anio->id)
        ->orderBy('nro_factura','DESC')
        ->first();

      $valor = (intval( $albaranes?->nro_factura??'0') +1);  
      $nroAl=  Basic::completarConCero($valor, 4);
      // # END asigna numero de albaran correlativo segun usuario id

      DB::beginTransaction();

      $aE = AlbaranesEnviado::create([
        'id_anio' => $anio->id,
        'user_id' =>  $userLoggedId,
        'cliente_id' =>  $data[0]->cliente->id,
        'importe' =>  $total,
        'fecha' =>  $request->fecha_emision,
        'cantidad' =>  $cantidad,
        'nro_factura' => $nroAl,
        'url' =>  'ALBARAN_' . $nroAl . '.pdf',
      ]);
      
      $this->saveAlbaranEnviadoItems($aE->id, $data);
      DB::commit();

      // Cargar el cliente con sus relaciones
      $cliente = Cliente::with(['provincia', 'pais'])->find($data[0]->cliente->id);
      
      $data = [
        'albaran' => $aE,
        'data' =>  $data,
        'userLog' => $user,
        'total' => $total,
        'nro_factura' =>  $nroAl,
        'fecha_emision' => Carbon::parse($request->fecha_emision)->format('d/m/Y'),
        'cliente' => $cliente
      ];

      $this->generatePDFAlbaranEnviado($data, $userLoggedId, $aE->url);

      return $data;   
    }catch(\Exception $e){
      DB::rollBack();
      return response()->json(['error' => $e->getMessage(), 'message' => 'Error al guardar albaran enviado'], 400);
    } 
  }

  // Actualizar albaran enviado
  public function updatealbaranesEnviadosF(Request $request, $idAlbaran){
    try{
      $userLoggedId = Auth::id();
      $user = User::where('id',Auth::id())->with('provincia')->first();

      $data =  json_decode($request->enviados);

      $total = 0;
      $cantidad = 0;
      foreach ($data as $value) {
        $total = (1*$total + 1*$this->parseHelper->parseEuroNumber($value->importe));
        $cantidad = $cantidad + intval($value->cantidad);
      }

      DB::beginTransaction();

      $aE = AlbaranesEnviado::findOrFail($idAlbaran);
      $aE->user_id =  $userLoggedId;
      $aE->cliente_id = $request->cliente_id;
      // $aE->precio =  $total;
      $aE->importe = $total;
      $aE->fecha = $request->fecha_emision;
      $aE->cantidad = $cantidad;
      $aE->update();

      $this->saveAlbaranEnviadoItems($aE->id, $data);
    
      // Cargar el cliente con sus relaciones
      $clienteGet = Cliente::with(['provincia', 'pais'])->find($request->cliente_id);
      
      $data = [
        'albaran' => $aE,
        'data' =>  $data,
        'userLog' => $user,
        'total' => $total,
        'nro_factura' =>  $aE->nro_factura,
        'fecha_emision' => $request->fecha_emision,
        'cliente' => $clienteGet,
      ];

      $this->generatePDFAlbaranEnviado($data, $userLoggedId, $aE->url);

      DB::commit();
      return  $data;
    }catch(\Exception $e){
      DB::rollBack();
      return response()->json(['error' => $e->getMessage(), 'message' => 'Error al actualizar albaran enviado'], 400);
    }
  }

  // Guardar items del albaran enviado
  private function saveAlbaranEnviadoItems($albaran_enviado_id, $data){
    $ids = [];
    foreach ($data as $value) {
      //tabla relacional
      $aeis = AlbaranEnviadoItemAgregado::updateOrCreate(
        ['id' => $value->id ?? null],
        [
          'albaran_enviado_id' => $albaran_enviado_id,
          'descripcion' => $value->descripcion,
          'cantidad' => intval($value->cantidad),
          'precio' => $this->parseHelper->parseEuroNumber($value->precio),
          'importe' => $this->parseHelper->parseEuroNumber($value->importe),
        ]
      );

      $ids[] = $aeis->id;
    }
    AlbaranEnviadoItemAgregado::where('albaran_enviado_id',$albaran_enviado_id)
      ->whereNotIn('id',$ids)
      ->delete();
  }

  // Generar PDF del albaran enviado
  private function generatePDFAlbaranEnviado($data, $userLoggedId, $url){
    // Asegurar directorio y escribir con permisos mediante el disco 'albaranes'
    $relativeDir = 'enviados/userId_'.$userLoggedId;
    if (!Storage::disk('albaranes')->exists($relativeDir)) {
      Storage::disk('albaranes')->makeDirectory($relativeDir);
      // Forzar permisos de lectura/ejecución en la carpeta userId
      @chmod(storage_path('app/public/albaranes/' . $relativeDir), 0775);
    }

    // Crear un objeto Recibo temporal con los datos del albarán para usar la vista new-recibo
    $recibo = new Recibo();
    $recibo->id = $data['albaran']->id;
    $recibo->cliente_id = $data['albaran']->cliente_id;
    $recibo->fecha = $data['albaran']->fecha;
    $recibo->sub_total = $data['total'];
    $recibo->total = $data['total'];
    $recibo->total_descuento = 0;
    $recibo->has_iva = false;
    $recibo->observaciones = '';
    $recibo->user_id = $userLoggedId;
    
    // Asignar el cliente
    $recibo->setRelation('cliente', $data['cliente']);
    
    // Convertir los items del albarán en ReciboServicio
    $showLote = $this->batchModeEnabledFor($userLoggedId);

    $servicios = collect($data['data'])->map(function($item) {
      $servicio = new ReciboServicio();
      $servicio->descripcion = $item->descripcion ?? '';
      $servicio->cantidad = intval($item->cantidad ?? 0);
      $servicio->precio = $this->parseHelper->parseEuroNumber($item->precio ?? 0);
      $servicio->importe = $this->parseHelper->parseEuroNumber($item->importe ?? 0);
      $servicio->iva_percent = null;
      $servicio->lote = $item->lote ?? null;
      return $servicio;
    });
    
    $recibo->setRelation('servicios', $servicios);
    
    // Crear relación temporal nro_nota para que new-recibo funcione correctamente
    // El albarán tiene id_anio, necesitamos cargar el AnioFiscal
    $albaran = $data['albaran'];
    if ($albaran->id_anio) {
      $anio = AnioFiscal::find($albaran->id_anio);
      if ($anio) {
        $nroNota = new NroNota();
        $nroNota->id_anio = $albaran->id_anio;
        $nroNota->setRelation('Anio', $anio);
        $recibo->setRelation('nro_nota', $nroNota);
      }
    }
    
    // Cargar el usuario con las relaciones necesarias
    $user = User::where('id', $userLoggedId)->with(['provincia', 'metodos_pago'])->first();
    $user->logo = $this->parseHelper->getLogoPath($user->avatar ?? '');
    
    // Formatear la fecha (usar la fecha del albarán directamente)
    $fecha = Carbon::parse($data['albaran']->fecha)->format('d-m-Y');
    
    // Generar el PDF usando la vista new-recibo
    $pdf = PDF::loadView('pdf.new-recibo', [
      'recibo' => $recibo,
      'fecha' => $fecha,
      'userLog' => $user,
      'nro_factura' => $data['nro_factura'],
      'tipo' => 'nota', // En new-recibo, 'nota' es albarán
      'show_lote' => $showLote,
    ]);
    $pdf->setPaper('A4');

    Storage::disk('albaranes')->put($relativeDir . '/' . $url, $pdf->output());
  }

  private function batchModeEnabledFor($userId)
  {
    static $cache = [];

    if (array_key_exists($userId, $cache)) {
      return $cache[$userId];
    }

    $value = SystemParam::where('business_id', $userId)
      ->where('param', ParamSystemEnum::ENABLE_BATCH->value)
      ->value('value');

    return $cache[$userId] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
  }

  // Eliminar albaran enviado
  public function deleteAlbaranEnviado($albaran_id){
    $albaran = AlbaranesEnviado::find($albaran_id);
    $albaran->delete();
    return response()->json('Albaran eliminado', 200);
  }

  // Generar factura
  public function generarFactura(Request $request){   
    $recibo = null;
    $nroFact = null;
    $Anio = AnioFiscal::latest()->first();
    try {
      $userLoged = User::where('id',$request->user_id)->first();

      $factura =  NroFactura::where(['user_id' => Auth::user()->id])->max('nro_factura');
      $valorFactura = (1*$factura + 1*1);  
      $strNr =  Basic::completarConCero($valorFactura, 4);
      $nroFactura = $strNr;
      
      DB::beginTransaction();
      
      $recibo = new Recibo;
      $recibo->user_id = $request->user_id;
      $recibo->cliente_id = $request->cliente_id;
      $recibo->fecha = date('Y-m-d');
      $recibo->sub_total = $this->parseHelper->parseEuroNumber($request->sub_total);
      $recibo->iva = $request->iva;
      $recibo->porcentaje_descuento = $request->porcentaje_descuento;
      $recibo->total_descuento = $request->total_descuento;
      $recibo->total  = $this->parseHelper->parseEuroNumber($request->total);
      $recibo->has_iva = $request->has_iva;
      $recibo->factura_url = 'FACTURA_' . $nroFactura. '.pdf';
      $recibo->save();

      $this->saveReciboServicios($recibo, $request->servicios, $request->user_id);

      $recibo->load('servicios', 'servicios.Servicio', 'cliente');

      $saveJsonAlbaran = AlbaranesEnviado::findOrFail(1*$request->albaran_id);
      $saveJsonAlbaran->json_recibo = json_encode($recibo);
      $saveJsonAlbaran->contabilizado  = 'FAC'.$nroFactura;
      $saveJsonAlbaran->update();

      $nroFact = new NroFactura;
      $nroFact->recibo_id = $recibo->id;
      $nroFact->nro_factura = $nroFactura;
      $nroFact->id_anio = $Anio->id;
      $nroFact->user_id = $userLoged->id;
      $nroFact->save();

      DB::commit();

      $data = [
        'nro_factura' => $nroFactura,
        'tipo' => 'factura',
        'recibo' => $recibo,
        'userLog' => $userLoged,
        'metodo' => [
          'nombre' => 'Transferencia Bancaria',
          'detalle' => null
        ]
      ];

      // Create directories if they don't exist
      $recibosPath = storage_path('app/public/recibos/userId_'. $request->user_id.'/');
      $facturasPath = storage_path('app/public/documentos/userId_'. $request->user_id . '/factura/');
      
      if (!file_exists($recibosPath)) {
          mkdir($recibosPath, 0755, true);
      }
      
      if (!file_exists($facturasPath)) {
          mkdir($facturasPath, 0755, true);
      }

      PDF::loadView('pdf.recibo', $data)->save($recibosPath . $recibo->factura_url);
      PDF::loadView('pdf.recibo', $data)->save($facturasPath . $recibo->factura_url);

      return response()->json(['data' => $data,'factura_generada' => true]);
    } catch (\Exception $e) {
      DB::rollBack();
      
      return response()->json([
        'error' => $e->getMessage(), 
        'message' => 'Error al generar factura'
      ], 400);
    }
  }

  private function saveReciboServicios($recibo, $servicios, $user_id){
    $ids = [];
    foreach($servicios as $servicio){
      $recioServicio = ReciboServicio::updateOrCreate(
        ['id' => $servicio->id ?? null],
        [
          'user_id' => $user_id,
          'recibo_id' => $recibo->id,
          'descripcion' => $servicio['descripcion'] ?? null,
          'cantidad' => intval($servicio['cantidad']),
          'precio' => $this->parseHelper->parseEuroNumber($servicio['precio']),
          'importe' => $this->parseHelper->parseEuroNumber($servicio['importe']),
          'lote' => $servicio['lote'] ?? null,
        ]
      );

      $ids[] = $recioServicio->id;
    }

    ReciboServicio::where('recibo_id', $recibo->id)
      ->whereNotIn('id', $ids)
      ->delete();
  }

  // Generar nota
  public function generarNota(Request $request){
    try{
      $userLoged = User::where('id',$request->user_id)->first();

      $Countfacturas = (1*count(NroNota::get()) + 1*1);
      $strNr = Basic::completarConCero($Countfacturas, 4);
      $nroNota =  $strNr;

      DB::beginTransaction();

      $recibo = new Recibo;
      $recibo->user_id = $request->user_id;
      $recibo->cliente_id = $request->cliente_id;
      $recibo->fecha = $request->fecha;
      $recibo->sub_total = $this->parseHelper->parseEuroNumber($request->sub_total);
      $recibo->iva = $request->iva;
      $recibo->porcentaje_descuento = $request->porcentaje_descuento;
      $recibo->total_descuento = $request->total_descuento;
      $recibo->total  = $this->parseHelper->parseEuroNumber($request->total);
      $recibo->has_iva = $request->has_iva;
      $recibo->nota_url = 'NOTA_'. $nroNota. '.pdf';
      $recibo->save();

      $this->saveReciboServicios($recibo, $request->servicios, $request->user_id);

      $saveJsonAlbaran = AlbaranesEnviado::findOrFail(1*$request->albaran_id);
      $saveJsonAlbaran->json_recibo = json_encode($recibo);
      $saveJsonAlbaran->contabilizado  = 'NOT'.$nroNota;
      $saveJsonAlbaran->update();

      $newNota = new NroNota;
      $newNota->recibo_id = $recibo->id;
      $newNota->nro_nota = $nroNota;
      $newNota->user_id = $userLoged->id;
      $newNota->save();

      DB::commit();

      $data = [
        'nro_factura' => $nroNota,
        'tipo' => 'nota',
        'recibo' => $recibo,
        'userLog' => $userLoged
      ];

      PDF::loadView('pdf.nota', $data)->save(storage_path('app/public/recibos/') . $recibo->nota_url);    

      return  response()->json(['data' => $data]);     
    }catch(\Exception $e){
      DB::rollBack();
      return response()->json([
        'error' => $e->getMessage(), 
        'message' => 'Error al generar nota'
      ], 400);
    }
  }

  // Mostrar albaran enviado
  public function albaranEnvidoShow (Request $request, $idAlbaran){
      $urlNota = null;
      $albaranEnviado = AlbaranesEnviado::with('itemAlbaran', 'cliente')->where('id', $idAlbaran)->first();
      // if($request->existente == true){
      //   if($request->cadena == 'NOT'){
      //       $urlNota = Nota::where('', )->first('');
      //   }
      // }
      return response()->json(['status' => 200,'albaranEnviado' => $albaranEnviado]);
  }  
}
