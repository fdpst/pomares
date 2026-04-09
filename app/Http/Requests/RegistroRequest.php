<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistroRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
          'name'     => 'required',
          'telefono' => 'required|max:9',
          'email'    => 'required|unique:users',
        ];
    }

    public function messages(){
        return [
          'name.required'     => 'El campo nombre es requerido',
          'telefono.required' => 'El campo telefono es requerido',
          'email.required'    => 'El campo email es requerido',
        ];
    }
}
