<?php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UsuarioCriadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Usuario $usuario,
        public readonly string $senhaPlana,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Suas credenciais de acesso — TriAL',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.usuario_criado',
        );
    }
}
