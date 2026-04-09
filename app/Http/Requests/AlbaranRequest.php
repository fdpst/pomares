<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlbaranRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
      return [
        'fecha'        => 'required',
        'proveedor_id' => 'required'
      ];
    }

    public function messages()
    {
        return [
          'fecha.required'        => 'Fecha es obligatorio',
          'proveedor_id.required' => 'Proveedor es obligatorio'
        ];
    }
}
