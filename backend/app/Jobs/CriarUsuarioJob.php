<?php

namespace App\Jobs;

use App\Domain\Usuario\Contracts\UsuarioRepositoryInterface;
use App\Mail\UsuarioCriadoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CriarUsuarioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(
        private readonly array $dados,
        private readonly string $senhaPlana,
    ) {}

    public function handle(UsuarioRepositoryInterface $repository): void
    {
        $usuario = $repository->criar($this->dados);

        Mail::to($usuario->email)->send(new UsuarioCriadoMail($usuario, $this->senhaPlana));

        Log::info(sprintf('[%s] CriarUsuarioJob: usuário %s criado e e-mail enviado.', now()->format('Y-m-d'), $usuario->email));
    }

    public function failed(Throwable $exception): void
    {
        Log::error(sprintf('[%s] CriarUsuarioJob FALHOU: %s', now()->format('Y-m-d'), $exception->getMessage()));
    }
}
