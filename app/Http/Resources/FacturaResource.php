<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FacturaResource extends JsonResource
{
  public function toArray($request)
  {
    $serie = isset($this->serie) ? $this->serie->serie : null;
    $nro_factura = null;
    if ($this->nro_factura) {
      $nro_factura = $serie . substr($this->nro_factura->Anio?->year, 2) . str_pad($this->nro_factura->nro_factura, 4, '0', STR_PAD_LEFT);
    }
    if ($this->nro_factura_rectificativa) {
      $nro_factura = $serie . substr($this->nro_factura_rectificativa->Anio?->year, 2) . str_pad($this->nro_factura_rectificativa->nro_factura, 4, '0', STR_PAD_LEFT);
    }

    $nro_factura_prof = null;
    if ($this->nro_factura_prof) {
      $nro_factura_prof = $serie . substr($this->nro_factura_prof->Anio?->year, 2) . str_pad($this->nro_factura_prof->nro_factura_prof, 4, '0', STR_PAD_LEFT);
    }

    return [
      'id'               => $this->id,
      'cliente'          => $this->cliente,
      'cliente_nombre'   => $this->cliente ? $this->cliente->nombre : null,
      'observaciones'    => $this->observaciones,
      'nro_factura'      => $nro_factura,
      'nro_factura_prof' => $nro_factura_prof,
      'enviado'          => $this->enviado,
      'fecha'            => Carbon::parse($this->fecha)->format('d-m-Y'),
      'fecha_pago'       => $this->fecha_pago ? Carbon::parse($this->fecha_pago)->format('d-m-Y') : null,
      'total'            => \App\Helpers\PriceHelper::formatWithSymbol((float) $this->total),
      'nombre_factura'   => $this->factura_url,
      'factura_path'     => Storage::url("recibos/userId_{$this->user_id}/{$this->factura_url}"),
      'pagado'           => $this->pagado,
      'servicios'        => $this->servicios,
      'factura_url'      => $this->factura_url,
      'presupuesto_url'  => $this->presupuesto_url,
      'nota_url'         => $this->nota_url,
      'user_id'          => $this->user_id,
    ];
  }
}
