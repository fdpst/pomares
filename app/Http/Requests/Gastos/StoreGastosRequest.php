<?php

namespace App\Http\Requests\Gastos;

use Illuminate\Foundation\Http\FormRequest;

class StoreGastosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'codigo'  => 'required',
            'importe' => 'required',
            'tipo_id' => 'required',
            'fecha'   => 'required'
        ];
    }


    public function messages()
    {
        return [
           'codigo.required'  => 'El campo código es requerido',
           'importe.required' =>  'El campo importe es requerido',
           'tipo_id.required' => 'El campo tipo es requerido',
          // 'archivo.required' => 'El campo archivo es requerido',
           'fecha.required'   => 'El campo fecha es requerido',
        ];
    }
}
