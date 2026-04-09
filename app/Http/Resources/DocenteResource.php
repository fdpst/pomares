<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocenteResource extends JsonResource
{
    public $preserveKeys = true;

    public function toArray($request)
    {
      return [
          'id'              => $this->id,
          'codigo'          => "PAO{$this->id}",
          'dni'             => $this->dni,
          'nombre'          => $this->nombre,
          'email'           => $this->email,          
          'created_at'      => $this->created_at->format('d-m-Y'),
      ];
    }
}
