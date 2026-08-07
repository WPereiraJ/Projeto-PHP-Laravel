<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginPesquisaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'cpf'   => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email'    => 'Insira um e-mail válido.',
            'cpf.required'   => 'O campo CPF/RG é obrigatório.',
        ];
    }
}