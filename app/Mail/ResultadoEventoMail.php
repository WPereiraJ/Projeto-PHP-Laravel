<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResultadoEventoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dadosAutor;
    public $posicao;

    public function __construct($dadosAutor, $posicao)
    {
        $this->dadosAutor = $dadosAutor;
        $this->posicao = $posicao;
    }

    public function build()
    {
        return $this->subject('Seu Resultado no [NOME DO EVENTO] ' . date('Y'))
                    ->view('emails.resultado-evento');
    }
}