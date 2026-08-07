<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inscricao;
use App\Models\AvaliadorTrabalho;

class Trabalho extends Model
{
    protected $table = 'trabalhos';
    protected $fillable = [
        'inscricao_id',
        'usuario_id', 
        'ano', 
        'status', 
        'clpi_aprovado',
        'data_apresentacao',
        'horario_apresentacao',
    ];

    public $timestamps = false;

    public function inscricao()
    {
        return $this->belongsTo(Inscricao::class, 'inscricao_id');
    }

    public function avaliadores()
    {
        return $this->belongsToMany(Usuario::class, 'avaliador_trabalhos', 'trabalhos_id', 'usuario_id')->withPivot('resumo', 'importancia', 'apresentacao', 'desempenho');
    }

    public function avaliacoes()
    {
        return $this->hasOne(AvaliadorTrabalho::class, 'trabalhos_id');
    }

    public function comissoes()
    {
        return $this->hasMany(Comissao::class, 'trabalho_id');
    }

    // App/Models/Trabalho.php

    public function minha_avaliacao_avaliador()
    {
        // Relacionamento para pegar a nota (Nível 0) do usuário logado
        return $this->hasOne(AvaliadorTrabalho::class, 'trabalhos_id')
            ->where('usuario_id', auth()->id());
    }

    public function meu_parecer_comissao()
    {
        // Relacionamento para pegar o comentário (Nível 1 e 2) do usuário logado
        return $this->hasOne(Comissao::class, 'trabalho_id')
            ->where('usuario_id', auth()->id());
    }
}

