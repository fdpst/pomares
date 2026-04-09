<?php

namespace App\Http\Resources;

use App\Models\Iva;
use Illuminate\Http\Resources\Json\JsonResource;
// use App\Models\Iva;

class FacturaLibroDiarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $subtotal = 0;
        $total = 0;
        $cuenta_iva = Iva::with(['CuentaContableRepercutido', 'CuentaContableSoportado']);
        $cuenta_iva_soportado = [];

        // Facturas recibidas
        if(isset($this->items)){
            foreach($this->items as $element){
                $subtotal_sin_dcto = $element->cantidad * $element->precio;
                $dcto = ($subtotal_sin_dcto * $element->dcto)/100;
                $subtotal += $subtotal_sin_dcto - $dcto;
                $total = $subtotal + ($subtotal * $element->iva)/100;

                $cuenta_iva = $cuenta_iva->where('descripcion', $element->iva)->first();
                $cuenta_iva_soportado[] = [
                    'title' => $element->iva, 
                    'cuenta' => isset($cuenta_iva->CuentaContableSoportado) ? $cuenta_iva->CuentaContableSoportado->numero : '',
                    'id' => isset($cuenta_iva->CuentaContableSoportado) ? $cuenta_iva->CuentaContableSoportado->id : null
                ];
            }
            
        }

        // Facturas enviadas (recibos)
        if(isset($this->servicios)){
            $subtotal_sin_dcto = 0;
            foreach($this->servicios as $element){
                $subtotal_sin_dcto += $element->cantidad * $element->precio;
            }
            $subtotal = $subtotal_sin_dcto - $this->total_descuento;
            $total = $subtotal + ($subtotal * $this->iva);

            $cuenta_iva = $cuenta_iva->where('descripcion', $this->tipo_iva)->first();
        }


        return [
            'id' => $this->id,
            'nro_factura' => $this->nro_factura,
            // 'id_pedido' => $this->id_pedido,
            'id_cliente' => $this->id_cliente,
            'iva' => $this->iva, //las facturas enviadas no tienen iva diferentes 
            // 'valor_iva' => ($subtotal * $this->iva)/100,
            'pdf' => $this->pdf,
            'subtotal' => $subtotal,
            'total' => $total,
            'cuenta_cliente' => isset($this->cliente) ? $this->cliente->cuenta : '',
            'cuenta_cliente_id' => isset($this->cliente->CuentaContable) ? $this->cliente->CuentaContable->id : '',

            'cuenta_proveedor' => isset($this->proveedor->CuentaContable) ? $this->proveedor->CuentaContable->numero : '',
            'cuenta_proveedor_id' => isset($this->proveedor->CuentaContable) ? $this->proveedor->CuentaContable->id : '',
            
            'cuenta_iva_repercutido' => isset($cuenta_iva->CuentaContableRepercutido) ? $cuenta_iva->CuentaContableRepercutido->numero : '',
            'cuenta_iva_repercutido_id' => isset($cuenta_iva->CuentaContableRepercutido) ? $cuenta_iva->CuentaContableRepercutido->id : '',

            'cuenta_iva_soportado' => $cuenta_iva_soportado,
            // 'cuenta_iva_soportado_id' => isset($cuenta_iva->CuentaContableSoportado) ? $cuenta_iva->CuentaContableSoportado->id : '',

            'lineas' => isset($this->items) ? $this->items : (isset($this->servicios) ? $this->servicios : []),
        ];
    }
}
