<?php


namespace App\Repositories\Activities\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StoreActivityNewRequest extends FormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'name'        => 'required|max:255',
            'time_real'   => 'required|date_format:H:i',
            'start_date'  => 'required|date',
            'due_date'    => 'required|date|after_or_equal:start_date',
            'tag_id'        => 'required'
        ];
    }
    public function messages()
    {
        return [
            'customer_id.required'    => "La empresa es obligatorio.",
            'customer_id.exists'      => "La empresa seleccionada no existe.",
            'name.required'           => "El nombre de la actividad obligatorio",
            'name.max'                => "No debe contener mas de 255 caracteres.",
            'time_real.required'      => "El tiempo es obligatorio",
            'time_real.date_format'   => "El tiempo debe ser H:m",
            'start_date.required'     => "Fecha inicial obligatorio",
            'start_date.date'         => "Fecha inicial formato incorrecto",
            'due_date.required'       => "Fecha final obligatorio",
            'due_date.date'           => "Fecha final incorrecto",
            'due_date.after_or_equal' => "La fecha final debe ser mayor o igual que la inicial",
            'tag_id.required'         => "La etiqueta es obligatorio"
        ];

    }
}
