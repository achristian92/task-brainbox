<?php


namespace App\Repositories\Activities\Requests;


use App\Repositories\Tags\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateActivityRequest extends FormRequest
{
    public function rules() {
        return [
            'customer_id'     => 'required|exists:customers,id',
            'user_id'         => 'required|exists:users,id',
            'name'            => 'required|max:255',
            'time_estimate'   => 'required|date_format:H:i',
            'start_date'      => 'required|date',
            'due_date'        => 'required|date|after_or_equal:start_date',
            'deadline'        => 'nullable|date|after_or_equal:start_date|before_or_equal:due_date',
            'tag_id.required' => "La etiqueta es obligatorio"
        ];
    }
    public function messages()
    {
        return [
            'customer_id.required'      => "La empresa es obligatorio.",
            'customer_id.exists'        => "La empresa seleccionada no existe.",
            'user_id.required'          => "El usuario es obligatorio.",
            'user_id.exists'            => "El usuario seleccionado no existe.",
            'name.required'             => "El nombre de la actividad obligatorio",
            'name.max'                  => "No debe contener mas de 255 caracteres.",
            'time_estimate.required'    => "El tiempo estimado es obligatorio",
            'time_estimate.date_format' => "El tiempo estimado debe ser H:m",
            'start_date.required'       => "Fecha inicial obligatorio",
            'start_date.date'           => "Fecha inicial formato incorrecto",
            'due_date.required'         => "Fecha final obligatorio",
            'due_date.date'             => "Fecha final formato incorrecto",
            'due_date.after_or_equal'   => "La fecha final debe ser mayor o igual que la inicial",
            'deadline.date'             => "La fecha límite  formato incorrecto",
            'deadline.after_or_equal'   => "La fecha límite  debe ser >= que la inicial",
            'deadline.before_or_equal'  => "La fecha límite  debe ser <= que la final",
            'tag_id.requierd'           => "La etiqueta es obligatorio"
        ];

    }
}
