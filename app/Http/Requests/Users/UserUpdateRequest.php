<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            // 'provincia_id' => 'required',
            // 'name' => 'required',
            // 'nombre_fiscal' => 'required',
            // 'cif' => 'required|max:9',
            // 'telefono' => 'required|max:9',
            // 'ciudad' => 'max:60',
            // 'email' => 'required|unique:users,email,' . $this->id,
            // 'role' => 'required'
        ];
    }

    public function messages(){
        return [
            'provincia_id.required' => 'El campo provincia es requerido',
            'name.required' => 'El campo nombre es requerido',
            'nombre_fiscal.required' => 'El campo nombre fiscal es requerido',
            'cif.required' => 'El campo cif es requerido',
            'cif.max' => 'El campo cif solo puede tener 9 caracteres',
            'telefono.max' => 'El campo telefono solo puede tener 9 caracteres',
            'telefono.required' => 'El campo telefono es requerido',
            'ciudad.max' => 'El campo ciudad solo puede tener 60 caracteres',
            
            'email.required' => 'El campo email es requerido',
            'role.required' => 'El campo rol es requerido'
        ];
    }
}
