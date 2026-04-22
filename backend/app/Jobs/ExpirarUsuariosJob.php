<?php

namespace App\Jobs;

use App\Domain\Usuario\Services\ExpirarUsuariosService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExpirarUsuariosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public function handle(ExpirarUsuariosService $service): void
    {
        $quantidade = $service->executar();

        Log::channel('single')->info(sprintf(
            '[%s] ExpirarUsuariosJob: %d usuário(s) expirado(s)',
            now()->format('Y-m-d'),
            $quantidade
        ));
    }

    public function failed(Throwable $exception): void
    {
        Log::error(sprintf(
            '[%s] ExpirarUsuariosJob FALHOU: %s',
            now()->format('Y-m-d'),
            $exception->getMessage()
        ));
    }
}
