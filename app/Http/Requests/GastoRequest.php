<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GastoRequest extends FormRequest
{
  public function authorize(){
      return true;
  }

  public function rules()
  {
      return [
        'codigo'  => 'required',
        'importe' => 'required'
      ];
  }

  public function messages()
  {
      return [
        'codigo.required'  => 'Codigo es obligatorio',
        'importe.required' => 'Importe es obligatorio'
      ];
  }
}
