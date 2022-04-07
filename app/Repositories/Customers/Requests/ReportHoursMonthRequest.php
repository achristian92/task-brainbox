<?php


namespace App\Repositories\Customers\Requests;


use Illuminate\Foundation\Http\FormRequest;

class ReportHoursMonthRequest extends FormRequest
{
    public function rules()
    {
        return [
            'yearAndMonth' => 'required|date_format:Y-m',
        ];
    }

    public function messages()
    {
        return [
            'yearAndMonth.required'    => "El mes es obligatorio.",
            'yearAndMonth.date_format' => "Formato incorrecto",
        ];
    }

}
