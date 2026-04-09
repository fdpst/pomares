<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MorososResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id'          => $this->id,
      'cliente'     => $this->deuda?->recibo?->cliente ? $this->deuda?->recibo?->cliente?->nombre : null,
      'tipo'        => $this->deuda_type == 'App\Models\NroFactura' ? 'Factura' : 'Nota',
      'nro_factura' => $this->deuda?->nro_factura ?
        str_pad($this->deuda?->nro_factura, 4, '0', STR_PAD_LEFT) :
        str_pad($this->deuda?->nro_nota, 4, '0', STR_PAD_LEFT),
      'fecha'       => $this->created_at->format('d-m-Y'),
      'total'       => $this->total,
      'pagado'      => $this->pagado,
      'deuda'       => $this->total - $this->pagado,
      'customer' => $this->deuda?->recibo?->cliente,
      'recibo_id' => $this->deuda?->recibo->id,
      'recibo' => $this->deuda?->recibo ?? []
    ];
  }
}
