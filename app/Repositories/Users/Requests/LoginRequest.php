<?php


namespace App\Repositories\Users\Requests;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules() {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ];
    }
}
