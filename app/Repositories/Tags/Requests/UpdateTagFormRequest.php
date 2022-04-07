<?php


namespace App\Repositories\Tags\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateTagFormRequest extends FormRequest
{
    public function rules() {
        return [
            'name' => 'required|unique:tags,name,'.$this->segment(4),
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
