<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvaliadorTrabalho extends Model
{
    protected $table = 'avaliador_trabalhos';
    public $timestamps = false;

    protected $fillable = [
        'trabalhos_id',
        'usuario_id',
        'resumo',
        'importancia',
        'apresentacao',
        'desempenho'
    ];

    // Relacionamento com o Trabalho
    public function trabalho()
    {
        return $this->belongsTo(Trabalho::class, 'trabalhos_id');
    }

    // Relacionamento com o Usuário
    public function avaliador()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}