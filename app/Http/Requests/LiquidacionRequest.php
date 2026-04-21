<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LiquidacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proveedor_id' => 'required|integer|exists:proveedores,id',
            'fecha' => 'required|date',
            'total' => 'nullable|numeric',
            'nro_factura' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'servicios' => 'required|string',
            'retencion_id' => 'nullable',
            'id' => 'nullable',
            'user_id' => 'nullable|integer|exists:users,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('proveedor_id') && $this->proveedor_id !== null && $this->proveedor_id !== '') {
            $this->merge(['proveedor_id' => (int) $this->proveedor_id]);
        }
        if ($this->has('user_id') && $this->user_id !== null && $this->user_id !== '' && $this->user_id !== 'null') {
            $this->merge(['user_id' => (int) $this->user_id]);
        }
    }
}
