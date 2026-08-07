<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Votacao extends Model
{
    protected $table = 'votacoes';
    public $timestamps = false;

    protected $guarded = ['*']; 

    public function trabalho()
    {
        return $this->belongsTo(Trabalho::class, 'trabalho_id');
    }
}