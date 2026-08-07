<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArquivoResumo extends Model
{
    protected $table = 'tb_arquivos_resumos';
    
    protected $fillable = ['inscricao_id', 'ano', 'tipo', 'nome_arquivo', 'arquivo'];
}