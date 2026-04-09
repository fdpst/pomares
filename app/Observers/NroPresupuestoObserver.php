<?php

namespace App\Observers;

use Auth;
use App\Models\NroPresupuesto;
use App\Models\AnioFiscal;

class NroPresupuestoObserver
{
  /*
    esto es solo para incrementar el numero del presupuesto
    al crear el registro -> al actualizar permanecera igual
  */
  public function creating(NroPresupuesto $nroPresupuesto)
  {
    $anio = AnioFiscal::orderBy('year','Desc')->first();

    // ANTIGUO
    // $contador = NroPresupuesto::withTrashed()->get()->count();
    // $nroPresupuesto->nro_presupuesto = $contador + 1;
    //
    // # START asigna numero de presupuesto correlativo segun usuario id
    // #  cambiado Oscar para numeracion correcta
    $presupuesto =  NroPresupuesto::where('user_id' , $nroPresupuesto->user_id)
      ->where('id_anio',$anio->id)
      ->where('id','!=',$nroPresupuesto->id)
      ->orderBy('nro_presupuesto', 'desc')
      ->first();
      
    $valorPresupuesto =1;
    if($presupuesto != null){
      $valorPresupuesto =  $presupuesto->nro_presupuesto+1;
    }
    $nroPresupuesto->nro_presupuesto = $valorPresupuesto;
    // # END asigna numero de presupuesto correlativo segun usuario id
  }
}
