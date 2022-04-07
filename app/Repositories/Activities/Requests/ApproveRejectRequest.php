<?php

namespace App\Repositories\Activities\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveRejectRequest extends FormRequest
{
    public function rules() {
        return [
            'activity_id' => 'required|exists:activities,id',
            'approved'   => 'required',
        ];
    }
    public function messages()
    {
        return [
            'activity_id.required' => "ID de la actividad obligatorio",
            'activity_id.exists'   => "La actividad no existe",
            'approved.required'    => "Tipo es obligatorio",
        ];
    }
}

