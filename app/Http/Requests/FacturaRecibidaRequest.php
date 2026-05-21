<?php

namespace App\Http\Requests;

use App\Helpers\GestorHelper;
use Illuminate\Foundation\Http\FormRequest;

class FacturaRecibidaRequest extends FormRequest
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

    protected function prepareForValidation(): void
    {
        $effectiveUserId = GestorHelper::getUserId($this);
        if ($effectiveUserId) {
            $this->merge(['user_id' => $effectiveUserId]);
        }

        if ($this->has('proveedor_id') && $this->proveedor_id !== null && $this->proveedor_id !== '') {
            $this->merge(['proveedor_id' => (int) $this->proveedor_id]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'proveedor_id' => 'required|integer',
            'user_id' => 'required|integer',
            'fecha' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'proveedor_id.required' => 'El proveedor es requerido',
            'user_id.required' => 'No se pudo determinar la empresa. Vuelva a iniciar sesión o seleccione un cliente.',
        ];
    }
}
