<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PresupuestoResource extends JsonResource
{
    public function toArray($request)
    {
      return [
        'id'      => $this->id,
        'cliente' => $this->cliente ? $this->cliente->nombre : null,
        'nro_presupuesto' => $this->nro_presupuesto ? ( substr($this->nro_presupuesto->Anio?->year,2). str_pad($this->nro_presupuesto->nro_presupuesto, 4, '0', STR_PAD_LEFT) ): null,
        'nro_presupuesto_prof' => $this->nro_presupuesto ? str_pad($this->nro_presupuesto->nro_presupuesto, 4, '0', STR_PAD_LEFT) : null,
        'anio'=>$this->nro_presupuesto->Anio,
        'fecha'   => $this->fecha,
        'total'   => $this->total,
        'nombre_presupuesto' => $this->presupuesto_url,
        'presupuesto_path'   => "storage/recibos/userId_{$this->user_id}/{$this->presupuesto_url}",
      ];
    }
}
