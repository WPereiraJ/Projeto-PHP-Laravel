<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatasEvento extends Model
{
    protected $table = 'tb_datas_evento';

    public $timestamps = false;

    protected $fillable = [
        'ano',
        'data_inicio_inscricoes',
        'data_final_inscricoes',
        'data_inicio_evento',
        'data_final_evento',
        'data_inicio_arquivo',
        'data_final_arquivo',
    ];

    protected $casts = [
        'data_inicio_inscricoes' => 'date:Y-m-d',
        'data_final_inscricoes' => 'date:Y-m-d',
        'data_inicio_evento' => 'date:Y-m-d',
        'data_final_evento' => 'date:Y-m-d',
        'data_inicio_arquivo' => 'date:Y-m-d',
        'data_final_arquivo' => 'date:Y-m-d',
    ];
}