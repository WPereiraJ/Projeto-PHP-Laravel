<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CadastroUsuario extends Mailable
{
    use Queueable, SerializesModels;

    // Propriedades públicas ficam automaticamente disponíveis na View
    public $name;
    public $email;
    public $senha;
    public $tipoUsuario;
    public $matricula;

    public function __construct($name, $email, $senha, $tipoUsuario, $matricula = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->senha = $senha;
        $this->tipoUsuario = $tipoUsuario;
        $this->matricula = $matricula;
    }

    public function build()
    {
        $subject = ($this->tipoUsuario === 'externo') 
            ? 'Credenciais de Acesso' 
            : 'Aviso de Cadastro no Sistema';

        return $this->subject($subject)
                    ->view('app.layouts._components.user.emails.cadastro');
    }
}