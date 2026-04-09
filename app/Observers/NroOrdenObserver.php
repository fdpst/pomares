<?php

namespace App\Observers;

use App\Models\NroOrden;

class NroOrdenObserver
{

  public function creating(NroOrden $nro_orden){
    $contador_orden = NroOrden::where('user_id', auth()->user()->id)
      ->get()
      ->count();
    $nro_orden->nro_orden = $contador_orden + 1;
  }
}
