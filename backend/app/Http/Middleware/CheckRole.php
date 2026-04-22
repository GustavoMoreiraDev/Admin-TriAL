<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        if (!in_array($usuario->role, $roles)) {
            return response()->json([
                'message' => 'Acesso negado. Permissão insuficiente.',
                'required' => $roles,
                'current'  => $usuario->role,
            ], 403);
        }

        return $next($request);
    }
}
