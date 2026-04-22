<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email'],
            'senha'            => ['required', 'string', 'min:6', 'confirmed'],
            'telefone'         => ['required', 'string', 'max:20'],
            'data_nascimento'  => ['required', 'date', 'before:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'            => 'O nome é obrigatório.',
            'email.required'           => 'O e-mail é obrigatório.',
            'email.email'              => 'Informe um e-mail válido.',
            'email.unique'             => 'Este e-mail já está em uso.',
            'senha.required'           => 'A senha é obrigatória.',
            'senha.min'                => 'A senha deve ter no mínimo 6 caracteres.',
            'senha.confirmed'          => 'A confirmação de senha não confere.',
            'telefone.required'        => 'O telefone é obrigatório.',
            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'data_nascimento.before'   => 'A data de nascimento deve ser anterior a hoje.',
        ];
    }
}
