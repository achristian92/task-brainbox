<?php


namespace App\Repositories\Activities\Requests;


use App\Repositories\Tags\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDuplicatePlannedRequest extends FormRequest
{
    public function rules() {
        return [
            'from_month' => 'required|date',
            'to_month'   => 'required|date|after:from_month',
        ];
    }
    public function messages()
    {
        return [
            'from_month.required' => "Mes inicial es obligatorio",
            'from_month.date'     => "Mes inicial con formato incorrecto",
            'to_month.required'   => "Mes final obligatorio",
            'to_month.date'       => "Mes final incorrecto",
            'to_month.after'      => "El mes hacia:  debe ser una mes posterior al mes de:"
        ];
    }
}
