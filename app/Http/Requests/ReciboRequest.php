<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReciboRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'cliente_id' => 'required',
      'fecha'      => 'required',
      'servicios'  => 'required',
      'sub_total'  => 'required',
      'iva'        => 'required',
      'descuento' => 'required',
      'total_descuento' => 'required',
      'total'   => 'required',
      'has_iva' => 'required',
      'fecha_recurrente' => 'nullable|integer|between:1,31'
    ];
  }

  public function messages()
  {
    return [
      'cliente_id.required' => 'Debe seleccionar un cliente',
      'fecha.required'      => 'Debe seleccionar una fecha',
      'servicios.required'  => 'Debe agregar al menos un servicio o albaran',
      'fecha_recurrente.between' => 'La fecha de recurrencia debe ser un dia del mes.',
    ];
  }
}
