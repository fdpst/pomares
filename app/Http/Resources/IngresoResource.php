<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IngresoResource extends JsonResource
{
    public function toArray($request)
    {
      return [
          'id'              => $this->id,
          'codigo'          => $this->cliente ? "AO{$this->cliente->id}" : null,
          'fecha'           => $this->fecha,
          'importe'         => $this->importe,
          'detalle'         => $this->detalle
      ];
    }
}
