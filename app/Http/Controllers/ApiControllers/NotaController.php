<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Resources\NotaResource;
use App\Http\Controllers\Controller;
use App\Mail\NotaLote;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Recibo;
use App\Helpers\GestorHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class NotaController extends Controller
{
  public function getNotas(Request $request, $user_id = null){
    // Usar el helper para obtener el user_id correcto (cliente_id si es gestor)
    $effectiveUserId = \App\Helpers\GestorHelper::getUserId($request);
    
    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }
    
    $query = GestorHelper::applyUserIdScope(Recibo::query(), $request)
      ->whereHas('nro_nota')
      ->with([
        'nro_nota.Anio', 
        'cliente',
        'reciboUnificado.nro_nota.Anio'
      ])
      ->orderBy('id', 'DESC')
      ->get();
    $notas = NotaResource::collection($query);
    return response()->json($notas, 200);
  }

  public function deleteNota($recibo_id){
    $recibo = Recibo::find($recibo_id);

    if($recibo->nota_url && Storage::disk('recibos')->exists('userId_' . $recibo->user_id . '/' . $recibo->nota_url)){
       Storage::disk('recibos')->delete('userId_' . $recibo->user_id . '/' . $recibo->nota_url);
    }

    $recibo->nro_nota->deuda->delete();

    $recibo->nro_nota()->delete();

    $recibo->nota_url = null;

    $recibo->save();

    return response()->json('nota eliminada', 200);
  }

  public function print(Request $request){
    try{
      $ids = [];
      foreach($request->elementos as $ele){
        $ids [] = $ele['id'];
      }
      $view = 'pdf.notas';
      if($request->resumen){
        $view = 'pdf.resumen_notas';
      }
      $notas = Recibo::whereIn('id',$ids)->get();
      $user = $request->User();
      $data = [];

      foreach($notas as $recibo){
        $data[]= [
          'recibo'   => $recibo,
          'fecha'    => Carbon::parse($recibo->fecha)->format('d-m-Y'),
          'userLog'  => $user,
          'nro_nota' => $recibo->nro_nota->nro_nota,
          'tipo'     => 'nota',
        ];
      } 

      $pdf = PDF::loadView($view,['data'=>$data] );

      $nombre_archivo = 'notas'.Carbon::now()->valueof() . '_'  . '.pdf';
      Storage::disk('recibos')->put('userId_'.$user->id.'/'.$nombre_archivo, $pdf->output());
      $file_path = Storage::disk('recibos')->path('userId_'.$user->id.'/'.$nombre_archivo);
      //Determinar archivos para gestor documental
      //$archivosGestorDocumental = $this->saveFilesForDocumentsApp($nombre_archivo, $recibo, $nro_factura, $tipo, $user->id, $pdf->output());
      return "storage/recibos/userId_{$user->id}/{$nombre_archivo}";
    }catch(\Exception $e){
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }

  public function SendLoteEmail(Request $request){
    foreach($request->elementos as $ele){
      if(isset($ele['cliente']['email'])){
        if($ele['cliente']['email'] != null){
          Mail::to($ele['cliente']['email'])->send(
            new NotaLote(
              'app/public/recibos/userId_'.$request->user()->id.'/'.$ele['nombre_nota']));//Se envía mail luego de crear el usuario
          Recibo::find($ele['id'])->update(['enviado'=>1]);
        }       
      }     
      return response()->json($request->all, 200);
    }
  }
}
