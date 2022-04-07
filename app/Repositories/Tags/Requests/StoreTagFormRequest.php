<?php


namespace App\Repositories\Tags\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StoreTagFormRequest extends FormRequest
{
    public function rules() {
        return [
            'name' => 'required|unique:tags,name',

        ];
    }
    public function messages()
    {
        return [
            'name.required' => "El nombre de la etiqueta es obligatorio.",
            'name.unique'   => "La etiqueta ya existe.",
        ];
    }
}
