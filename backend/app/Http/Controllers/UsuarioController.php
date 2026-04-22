<?php

namespace App\Http\Controllers;

use App\Domain\Usuario\Contracts\UsuarioRepositoryInterface;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Jobs\CriarUsuarioJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UsuarioController extends Controller
{
    public function __construct(
        private readonly UsuarioRepositoryInterface $usuarioRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filtros = $request->only(['search', 'role', 'status']);
        $perPage = min((int) $request->get('per_page', 15), 100);

        $usuarios = $this->usuarioRepository->listarTodos($perPage, $filtros);

        return response()->json($usuarios);
    }

    public function store(StoreUsuarioRequest $request): JsonResponse
    {
        $dados = $request->validated();
        $senhaPlana = $dados['senha'];

        if (empty($dados['data_expiracao'])) {
            $dados['data_expiracao'] = Carbon::today()->addDays(7);
        }

        $dados['status'] = 'ativo';

        CriarUsuarioJob::dispatch($dados, $senhaPlana);

        return response()->json([
            'message' => 'Usuário em processamento. As credenciais serão enviadas por e-mail em instantes.',
        ], 202);
    }

    public function show(int $id): JsonResponse
    {
        $usuario = $this->usuarioRepository->buscarPorId($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        return response()->json(['usuario' => $usuario]);
    }

    public function update(UpdateUsuarioRequest $request, int $id): JsonResponse
    {
        $usuario = $this->usuarioRepository->buscarPorId($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        $usuario = $this->usuarioRepository->atualizar($usuario, $request->validated());

        return response()->json([
            'message' => 'Usuário atualizado com sucesso.',
            'usuario' => $usuario,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $usuario = $this->usuarioRepository->buscarPorId($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        $this->usuarioRepository->deletar($usuario);

        return response()->json(['message' => 'Usuário removido com sucesso.']);
    }
}
