<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use App\Models\NroNota;
use App\Models\AnioFiscal;

class NroNotaObserver
{
  /*
    esto es solo para incrementar el numero de la nota
    al crear el registro -> al actualizar permanecera igual
  */
  public function creating(NroNota $nroNota)
  {
    $anio = AnioFiscal::orderBy('year','Desc')->first();

    // # START asigna numero de nota correlativo segun usuario id
    // #  cambiado para numeracion correcta (igual que facturas y presupuestos)
    $nota =  NroNota::where(['user_id' => $nroNota->user_id])
      ->where('id_anio',$anio->id)
      ->where('id','!=',$nroNota->id)
      ->orderBy('nro_nota', 'desc')
      ->first();
      
    if ($nota == null) {
      $valorNota = 1;
    } else {
      $valorNota = ($nota->nro_nota + 1);
    }
    
    $nroNota->nro_nota = $valorNota;
    $nroNota->id_anio = $anio->id;
    // # END asigna numero de nota correlativo segun usuario id
  }
}
