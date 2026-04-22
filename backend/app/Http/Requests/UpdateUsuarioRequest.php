<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $usuarioId = $this->route('usuario');

        return [
            'nome'            => ['sometimes', 'string', 'max:255'],
            'email'           => ['sometimes', 'email', Rule::unique('usuarios', 'email')->ignore($usuarioId)],
            'senha'           => ['sometimes', 'string', 'min:6'],
            'telefone'        => ['sometimes', 'string', 'max:20'],
            'data_nascimento' => ['sometimes', 'date', 'before:today'],
            'role'            => ['sometimes', 'in:administrador,editor,consultor'],
            'status'          => ['sometimes', 'in:ativo,expirado'],
            'data_expiracao'  => ['sometimes', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email'            => 'Informe um e-mail válido.',
            'email.unique'           => 'Este e-mail já está em uso.',
            'senha.min'              => 'A senha deve ter no mínimo 6 caracteres.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
            'role.in'                => 'Perfil inválido. Use: administrador, editor ou consultor.',
            'status.in'              => 'Status inválido. Use: ativo ou expirado.',
        ];
    }
}
