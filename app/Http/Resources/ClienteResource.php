<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ClienteResource extends JsonResource
{
    public $preserveKeys = true;

    public function toArray($request)
    {
      return [
          'id'               => $this->id,
          'nro_cliente'      => $this->nro_cliente,
          'dni'              => $this->dni,
          'nombre'           => $this->nombre,
          'nombre_comercial' => $this->nombre_comercial,
          'email'            => $this->email,
          'telefono'         => $this->telefono,
          'provincia'        => $this->provincia ? $this->provincia->nombre : null,
          'activo'           => $this->activo,
          'fecha_alta'       => Carbon::parse($this->fecha_alta)->format('d-m-Y'),
          'created_at'       => $this->created_at->format('d-m-Y'),
      ];
    }
}
