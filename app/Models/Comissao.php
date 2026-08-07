<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comissao extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'tb_comissao';
    protected $fillable = ['trabalho_id', 'usuario_id', 'comentarios', 'inscricao_id', 'arquivos'];
    public $timestamps = false;
}