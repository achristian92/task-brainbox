<?php


namespace App\Repositories\Users\Requests;


use Illuminate\Foundation\Http\FormRequest;

class ReportUserCustomerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id'      => 'required|exists:users,id',
            'yearAndMonth' => 'required|date_format:Y-m',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required'         => "Selecciona un usuario.",
            'user_id.exists'           => "Usuario no existe.",
            'yearAndMonth.required'    => "El mes es obligatorio.",
            'yearAndMonth.date_format' => "Formato incorrecto",
        ];
    }
}
