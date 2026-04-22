<?php

namespace App\Http\Controllers;

use App\Domain\Usuario\Contracts\UsuarioRepositoryInterface;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        private readonly UsuarioRepositoryInterface $usuarioRepository
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $usuario = $this->usuarioRepository->criar([
            'nome'            => $request->nome,
            'email'           => $request->email,
            'senha'           => $request->senha,
            'telefone'        => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'status'          => 'ativo',
            'data_expiracao'  => now()->addDays(7)->toDateString(),
        ]);

        $token = Auth::login($usuario);

        return response()->json([
            'message' => 'Cadastro realizado com sucesso.',
            'usuario' => $usuario,
            'token'   => $token,
            'type'    => 'bearer',
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->senha,
        ];

        if (! $token = Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'token'   => $token,
            'type'    => 'bearer',
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'usuario' => Auth::user(),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();
        $dados   = $request->validated();

        if (isset($dados['senha'])) {
            if (empty($dados['senha_atual']) || !Hash::check($dados['senha_atual'], $usuario->senha)) {
                return response()->json(['message' => 'Senha atual incorreta.'], 422);
            }
            unset($dados['senha_atual']);
        } else {
            unset($dados['senha_atual']);
        }

        $usuario = $this->usuarioRepository->atualizar($usuario, $dados);

        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
            'usuario' => $usuario,
        ]);
    }

    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'message' => 'Logout realizado com sucesso.',
        ]);
    }
}
