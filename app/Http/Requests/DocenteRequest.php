<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocenteRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules()
    {
        return [
          'nombre' => 'required',
          'dni'    => 'required'
        ];
    }

    public function messages()
    {
        return [
          'nombre.required' => 'Nombre es obligatorio',
          'dni.required'    => 'DNI es obligatorio'
        ];
    }
}
