<?php

namespace App\Domain\Usuario\Contracts;

use App\Models\Usuario;
use Illuminate\Support\Collection;

interface UsuarioRepositoryInterface
{
    public function criar(array $dados): Usuario;

    public function buscarPorEmail(string $email): ?Usuario;

    public function buscarAtivosExpirados(): Collection;

    public function marcarComoExpirados(Collection $usuarios): int;

    /** @return \Illuminate\Pagination\LengthAwarePaginator */
    public function listarTodos(int $perPage = 20, array $filtros = []);

    public function buscarPorId(int $id): ?Usuario;

    public function atualizar(Usuario $usuario, array $dados): Usuario;

    public function deletar(Usuario $usuario): void;
}
