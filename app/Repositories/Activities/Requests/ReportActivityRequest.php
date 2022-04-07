<?php


namespace App\Repositories\Activities\Requests;


use Illuminate\Foundation\Http\FormRequest;

class ReportActivityRequest extends FormRequest
{
    public function rules() {
        return [
            'start_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:start_date',
        ];
    }
    public function messages()
    {
        return [
            'start_date.required'     => "Fecha inicial obligatorio",
            'start_date.date'         => "Fecha inicial incorrecto",
            'due_date.required'       => "Fecha final obligatorio",
            'due_date.date'           => "Fecha final incorrecto",
            'due_date.after_or_equal' => "La fecha final debe ser mayor o igual que la inicial"
        ];
    }
}

