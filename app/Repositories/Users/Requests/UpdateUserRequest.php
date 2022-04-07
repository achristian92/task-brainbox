<?php


namespace App\Repositories\Users\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest  extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'last_name' => 'required',
            'nro_document' => 'required|unique:users,nro_document,'.$this->user_id,
            'email' => 'required|email|unique:users,email,'.$this->user_id,
            'image' => ['file','image:png,jpeg,jpg','max:548'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "El nombre del administrador es obligatorio.",
            'last_name.required' => "El apellido del administrador es obligatorio.",
            'email.required' => "El correo electronico del administrador es obligatorio.",
            'email.email' => "El correo electronico es inválido.",
            'email.unique' => "El valor del campo email ya está en uso.",
        ];
    }
}
