<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Usuario\Contracts\UsuarioRepositoryInterface;
use App\Models\Usuario;
use Illuminate\Support\Collection;

class UsuarioRepository implements UsuarioRepositoryInterface
{
    public function criar(array $dados): Usuario
    {
        return Usuario::create($dados);
    }

    public function buscarPorEmail(string $email): ?Usuario
    {
        return Usuario::where('email', $email)->first();
    }

    public function buscarAtivosExpirados(): Collection
    {
        return Usuario::where('status', 'ativo')
            ->whereDate('data_expiracao', '<', now()->toDateString())
            ->get();
    }

    public function marcarComoExpirados(Collection $usuarios): int
    {
        $ids = $usuarios->pluck('id');

        return Usuario::whereIn('id', $ids)
            ->update(['status' => 'expirado']);
    }

    public function listarTodos(int $perPage = 20, array $filtros = [])
    {
        $query = Usuario::query()->orderBy('created_at', 'desc');

        if (!empty($filtros['search'])) {
            $search = $filtros['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        if (!empty($filtros['role'])) {
            $query->where('role', $filtros['role']);
        }

        if (!empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        return $query->paginate($perPage);
    }

    public function buscarPorId(int $id): ?Usuario
    {
        return Usuario::find($id);
    }

    public function atualizar(Usuario $usuario, array $dados): Usuario
    {
        $usuario->update($dados);
        return $usuario->fresh();
    }

    public function deletar(Usuario $usuario): void
    {
        $usuario->delete();
    }
}
