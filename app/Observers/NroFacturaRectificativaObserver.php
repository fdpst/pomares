<?php

namespace App\Observers;

use Auth;
use App\Models\NroFacturaRectificativa;
use App\Models\AnioFiscal;


class NroFacturaRectificativaObserver
{
  /*
    esto es solo para incrementar el numero de la nota
    al crear el registro -> al actualizar permanecera igual
  */
  public function creating(NroFacturaRectificativa $nroFactura)
  {
    $anio = AnioFiscal::orderBy('year','Desc')->first();

    // ANTIGUO
    // $contador = NroFactura::get()->count();
    // $nroFactura->nro_factura = $contador + 1;
    //
    // # START asigna numero de factura correlativo segun usuario id
    // #  cambiado Oscar para numeracion correcta
    $factura =  NroFacturaRectificativa::where(['user_id' => $nroFactura->user_id])->where('id_anio',$anio->id)->where('id','!=',$nroFactura->id)->orderBy('id', 'desc')->first();
    if($factura == null){
      $valorFactura = 1;
    }else{
      $valorFactura = ($factura->nro_factura + 1);
    }
    
    $nroFactura->nro_factura = $valorFactura;
    // # END asigna numero de factura correlativo segun usuario id    
  }
}
