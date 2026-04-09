<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class NotaResource extends JsonResource
{
    public function toArray($request)
    {
      $nro_nota = null;
      if (isset($this->nro_nota)) {
        $nro_nota = substr($this->nro_nota->Anio?->year, 2) . str_pad($this->nro_nota->nro_nota, 4, '0', STR_PAD_LEFT);
      }

      // Obtener el número de albarán del recibo unificado si existe
      $nro_albaran_unificado = null;
      if ($this->unificado && $this->reciboUnificado && $this->reciboUnificado->nro_nota) {
        $nro_albaran_unificado = substr($this->reciboUnificado->nro_nota->Anio?->year ?? date('Y'), 2) . 
          str_pad($this->reciboUnificado->nro_nota->nro_nota, 4, '0', STR_PAD_LEFT);
      }

      return [
        'id'            => $this->id,
        'cliente'       => $this->cliente,
        'observaciones' => $this->observaciones,
        'nro_nota'      => $nro_nota,
        'fecha'         => $this->fecha,
        'total'         => $this->total,
        'nombre_nota'   => $this->nota_url,
        'nota_path'     => $this->nota_url ? url('storage/recibos/userId_' . $this->user_id . '/' . $this->nota_url) : null,
        'pagado'        => $this->pagado,
        'unificado'     => $this->unificado ?? false,
        'recibo_unificado_id' => $this->recibo_unificado_id,
        'nro_albaran_unificado' => $nro_albaran_unificado
      ];
    }
}


