<?php


namespace App\Repositories\Customers\Requests;


use Illuminate\Foundation\Http\FormRequest;

class ReportListCounterWorkedRequest extends FormRequest
{
    public function rules()
    {
        return [
            'customer_id'  => 'required|exists:customers,id',
            'yearAndMonth' => 'required|date_format:Y-m',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required'     => "Selecciona un cliente.",
            'customer_id.exists'       => "Cliente no existe.",
            'yearAndMonth.required'    => "El mes es obligatorio.",
            'yearAndMonth.date_format' => "Formato incorrecto",
        ];
    }
}
