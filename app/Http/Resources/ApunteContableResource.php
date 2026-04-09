<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApunteContableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $nombre_cuenta = '';
        if(isset($this->cuentaContable->Nombre) && isset($this->cuentaContable->Apellido)){
            $nombre_cuenta = $this->cuentaContable->Nombre->nombre.' '.$this->cuentaContable->Apellido->nombre;
        }else{
            $nombre_cuenta = 'Sin informacion';
        }

        $linea = '';
        if($this->debe == 0){
            $linea = 1;
        }else{
            $linea = 2;
        }

        return [
            'id' => $this->id,
            'apunte_contable_id' => $this->apunte_contable_id,
            'fecha' => isset($this->apunteContable) ? $this->apunteContable->fecha : 'Sin informaion',
            'numero_cuenta' => isset($this->cuentaContable) ? $this->cuentaContable->numero : 'Sin informacion',
            'nombre_cuenta' => $nombre_cuenta,
            'linea' => $linea,
            'descripcion' => $this->descripcion,
            'documento' => $this->documento,
            'tipo' => isset($this->apunteContable->tipoApunte) ? $this->apunteContable->tipoApunte->descripcion : 'Sin informacion',
            'debe' => $this->debe,
            'haber' => $this->haber,
        ];
    }
}
