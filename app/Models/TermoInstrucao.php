<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermoInstrucao extends Model
{
    protected $table = 'tb_termos_instrucoes';
    protected $fillable = ['termos', 'instrucoes'];
    public $timestamps = false;
}