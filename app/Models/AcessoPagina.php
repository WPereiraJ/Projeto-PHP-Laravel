<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcessoPagina extends Model
{
    protected $table = 'tb_acesso_paginas';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'pagina_id',
        'acessar',
        'incluir',
        'editar',
        'excluir'
    ];
}