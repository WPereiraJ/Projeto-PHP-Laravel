<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagina extends Model
{
    protected $table = 'tb_paginas';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'rota',
    ];
}