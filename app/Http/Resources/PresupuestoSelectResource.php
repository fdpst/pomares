<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PresupuestoSelectResource extends JsonResource
{
    public function toArray($request)
    {
      return [
        'nro_presupuesto_id' => $this->id,
        'nro_presupuesto'    => str_pad($this->nro_presupuesto, 4, '0', STR_PAD_LEFT),
        'total'              => $this->recibo->total
      ];
    }
}
