<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules()
    {
        return [
          'nombre'    => 'required',
          'telefono'  => 'numeric|digits_between:1,9|nullable'
        ];
    }

    public function messages()
    {
        return [
          'nombre.required'  => 'Nombre es obligatorio',
          'telefono.numeric' => 'Teléfono debe ser numerico'
        ];
    }
}
