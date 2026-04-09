<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParteTrabajoResource extends JsonResource
{
    public function toArray($request)
    {
      return [
        'id' => $this->id,
        $this->mergeWhen($this->nro_parte_trabajo->presupuesto()->exists(), function() {
          return [
            'nro_presupuesto'  => str_pad($this->nro_parte_trabajo->presupuesto->nro_presupuesto, 4, '0', STR_PAD_LEFT)
          ];
        }),
        $this->mergeWhen($this->nro_parte_trabajo->presupuesto()->exists() && $this->nro_parte_trabajo->presupuesto->recibo()->exists(), function(){
          return [
            'total_presupuesto' => "{$this->nro_parte_trabajo->presupuesto->recibo->total}€",
            'beneficio'         => number_format(floatval($this->nro_parte_trabajo->presupuesto->recibo->total - floatval($this->total)), 2)
          ];
        }),
        'cliente'           => $this->cliente ? $this->cliente->nombre : null,
        'nro_parte_trabajo' => $this->nro_parte_trabajo ? str_pad($this->nro_parte_trabajo->nro_parte_trabajo, 4, '0', STR_PAD_LEFT) : null,
        'fecha'             => $this->fecha,
        'total'             => "{$this->total}€",
      ];

      /*$this->mergeWhen(Auth::user()->isAdmin(), [
            'first-secret' => 'value',
            'second-secret' => 'value',
        ]),*/
    }
}
