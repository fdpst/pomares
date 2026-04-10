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
            'proveedor_id' => 'required',
            'user_id' => 'required',
            'fecha' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'proveedor_id.required' => 'El distribuidor es requerido',
        ];
    }
}
