<?php

namespace App\Domain\Usuario\Services;

use App\Domain\Usuario\Contracts\UsuarioRepositoryInterface;

class ExpirarUsuariosService
{
    public function __construct(
        private readonly UsuarioRepositoryInterface $usuarioRepository
    ) {}

    public function executar(): int
    {
        $usuarios = $this->usuarioRepository->buscarAtivosExpirados();

        if ($usuarios->isEmpty()) {
            return 0;
        }

        return $this->usuarioRepository->marcarComoExpirados($usuarios);
    }
}
