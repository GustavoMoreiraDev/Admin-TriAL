<?php

namespace App\Providers;

use App\Domain\Usuario\Contracts\UsuarioRepositoryInterface;
use App\Infrastructure\Repositories\UsuarioRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UsuarioRepositoryInterface::class,
            UsuarioRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
