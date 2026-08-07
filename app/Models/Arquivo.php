<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    protected $table = 'tb_arquivos';
    public $timestamps = false;

    protected $fillable = [
        'nome_arquivo',
        'arquivo', // Coluna BLOB
        'ano',
    ];
}
