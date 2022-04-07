<?php


namespace App\Repositories\Customers\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerFormRequest extends FormRequest
{
    public function rules() {
        return [
            'name'          => 'required|unique:customers,name',
            'hours'         => 'nullable|numeric',
            'contact_email' => 'nullable|email',
            'image'         => ['file','image:png,jpeg,jpg','max:548'],
        ];
    }
    public function messages()
    {
        return [
            'name.required'       => "El nombre del cliente es obligatorio.",
            'name.unique'         => "El nombre ya existe.",
            'contact_email.email' => "El correo de contacto es incorrecto.",
            'hours.numeric'       => 'Horas mensuales debe ser númerico.'
        ];
    }

}
