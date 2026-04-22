<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RootUsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ROOT_EMAIL', 'root@trial.com');

        // Idempotente: atualiza se já existe, cria se não existe
        Usuario::updateOrCreate(
            ['email' => $email],
            [
                'nome'            => env('ROOT_NOME', 'Administrador'),
                'senha'           => env('ROOT_SENHA', 'root123'),   
                'telefone'        => env('ROOT_TELEFONE', '41998990586'),
                'data_nascimento' => env('ROOT_NASCIMENTO', '2000-02-02'),
                'role'            => 'administrador',
                'status'          => 'ativo',
                'data_expiracao'  => Carbon::now()->addYears(99)->toDateString(),
            ]
        );

        $this->command->info("Root user garantido: {$email}");
    }
}
