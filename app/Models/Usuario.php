<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;
    protected $table = 'tb_usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'nivel_de_acesso_user',
        'ativo',
        'usuario_padrão',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // --- RELACIONAMENTOS ---

    public function avaliacoes_realizadas()
    {
        return $this->hasMany(AvaliadorTrabalho::class, 'usuario_id');
    }

    public function trabalhosAvaliados()
    {
        return $this->belongsToMany(Trabalho::class, 'avaliador_trabalhos', 'usuario_id', 'trabalhos_id');
    }

    public function acessoPaginas()
    {
        return $this->hasMany(AcessoPagina::class, 'usuario_id');
    }

    // --- ACESSORS E MÉTODOS DE AUTH ---

    public function getMmatrAttribute()
    {
        return $this->usuario_padrão;
    }

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function getNomeCargoAttribute()
    {
        $cargos = [
            6 => 'CTI',
            5 => 'Desenvolvedor',
            4 => 'P&D',
            3 => 'Presidente',
            2 => 'CLPI',
            1 => 'Comissão',
            0 => 'Avaliador'
        ];

        return $cargos[$this->nivel_de_acesso_user] ?? 'Desconhecido';
    }

    // --- REGRAS DE NEGÓCIO (PERMISSÕES) ---

    /**
     * Define quem pode cadastrar qual nível.
     */
    public function podeCadastrarNivel($nivelAlvo): bool
    {
        $meuNivel = (int) $this->nivel_de_acesso_user;
        $nivelAlvo = (int) $nivelAlvo;

        // Regra: Dev (5) -> P&D (4)
        if ($meuNivel === 5)
            return $nivelAlvo === 4;

        // Regra: P&D (4) -> Presidente (3)
        if ($meuNivel === 4)
            return $nivelAlvo === 3;

        // Regra: Presidente (3) -> Todos, exceto P&D(4) e Dev(5)
        if ($meuNivel === 3)
            return !in_array($nivelAlvo, [4, 5]);

        // Comissão (1) -> Avaliador(0), CLPI(2), CTI(6)
        if ($meuNivel === 1)
            return in_array($nivelAlvo, [0, 2, 6]);

        return false;
    }

    /**
     * Define quem pode editar/desativar quem.
     */
    public function podeGerenciarUsuario(Usuario $usuarioAlvo): bool
    {
        $meuNivel = (int) $this->nivel_de_acesso_user;
        $nivelAlvo = (int) $usuarioAlvo->nivel_de_acesso_user;

        // Se for o próprio usuário
        if ($this->id === $usuarioAlvo->id) return true;

        if ($meuNivel === 5)
            return true; // Dev total
        if ($meuNivel === 4)
            return false; // P&D não edita

        // Presidente gere todos abaixo dele
        if ($meuNivel === 3)
            return !in_array($nivelAlvo, [4, 5]);

        // Comissão gere os que ela cria
        if ($meuNivel === 1)
            return in_array($nivelAlvo, [0, 2, 6]);

        return false;
    }

    /**
     * Define quem acessa a página de Gestão de Usuários.
     */
    public function podeGerirUsuarios(): bool
    {
        // Dev(5), P&D(4), Presidente(3) e Comissão(1)
        return in_array((int) $this->nivel_de_acesso_user, [1, 3, 4, 5]);
    }

    public function podeEditarUsuarios(): bool
    {
        return in_array((int) $this->nivel_de_acesso_user, [1, 3, 5]);
    }
}