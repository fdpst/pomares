<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules()
    {
        return [
          'nombre'        => 'required',
          'dni'           => 'required',
          // START cambios para cientes con Pais
          'provincia_id'     => 'required',
          // END cambios para cientes con Pais
          'codigo_postal' => 'numeric|digits_between:1,5|nullable',
          'telefono'      => 'nullable'
        ];
    }

    public function messages()
    {
        return [
          'nombre.required'       => 'Nombre es obligatorio',
          'dni.required'          => 'DNI es obligatorio',
          'codigo_postal.numeric' => 'Codigo postal debe ser numerico',
          'telefono.numeric'      => 'Teléfono debe ser numerico',
          // START cambios para cientes con Pais
          'provincia_id.required'    => 'La Provincia es obligatoria'
          // END cambios para cientes con Pais
        ];
    }
}
