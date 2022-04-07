<?php


namespace App\Repositories\Users\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules() {
        return [
            'name' => 'required',
            'last_name' => 'required',
            'nro_document' => 'required|unique:users,nro_document',
            'email' => 'required|email|unique:users,email',
            'image' => ['file','image:png,jpeg,jpg','max:548'],

        ];
    }
    public function messages()
    {
        return [
            'name.required' => "El nombre es obligatorio.",
            'last_name.required' => "El apellido es obligatorio.",
            'email.required' => "El correo electronico es obligatorio.",
            'email.email' => "El correo electronico es inválido.",
            'email.unique' => "El correo electronico ya esta en uso.",
        ];
    }
}
