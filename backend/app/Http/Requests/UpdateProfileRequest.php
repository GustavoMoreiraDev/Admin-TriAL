<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'nome'            => ['sometimes', 'string', 'max:255'],
            'telefone'        => ['sometimes', 'string', 'max:20'],
            'data_nascimento' => ['sometimes', 'date', 'before:today'],
            'email'           => ['sometimes', 'email', Rule::unique('usuarios', 'email')->ignore($userId)],
            'senha_atual'     => ['sometimes', 'string'],
            'senha'           => ['sometimes', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email'            => 'Informe um e-mail válido.',
            'email.unique'           => 'Este e-mail já está em uso.',
            'senha.min'              => 'A nova senha deve ter no mínimo 6 caracteres.',
            'senha.confirmed'        => 'A confirmação de senha não confere.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
        ];
    }
}
