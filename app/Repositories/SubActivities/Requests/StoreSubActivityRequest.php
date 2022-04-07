<?php


namespace App\Repositories\SubActivities\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubActivityRequest extends FormRequest
{
    public function rules() {
        return [
            'name'     => 'required|max:255',
            'duration' => 'required|date_format:H:i',
        ];
    }
    public function messages()
    {
        return [
            'name.required'        => "Este campo es obligatorio",
            'name.max'             => "No debe contener mas de 255 caracteres.",
            'duration.required'    => "Este campo es obligatorio",
            'duration.date_format' => "La duración debe ser H:m",
        ];

    }
}
