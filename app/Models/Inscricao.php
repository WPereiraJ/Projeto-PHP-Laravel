<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    protected $table = 'tb_inscricoes';
    public $timestamps = false;
    protected $guarded = ['id'];

    // Lista de campos permitidos para inserção em massa
    protected $fillable = [
        'nome_autor',
        'nome_trabalho',
        'cpf_rg',
        'afiliacao_autor',
        'email_autor',
        'orientador',
        'email_orientador',
        'resumo',
        'termos_indexacao',
        'financiamento',
        'seg_contrato',
        'categoria',
        'termos',
        'sisgen',
        'arquivo',      
        'nome_arquivo', 
        
        // Co-autores (do 2 ao 10)
        'nome_autor2', 'afiliacao_autor2',
        'nome_autor3', 'afiliacao_autor3',
        'nome_autor4', 'afiliacao_autor4',
        'nome_autor5', 'afiliacao_autor5',
        'nome_autor6', 'afiliacao_autor6',
        'nome_autor7', 'afiliacao_autor7',
        'nome_autor8', 'afiliacao_autor8',
        'nome_autor9', 'afiliacao_autor9',
        'nome_autor10', 'afiliacao_autor10',
    ];


    public function trabalho()
    {
        return $this->hasOne(Trabalho::class, 'inscricao_id');
    }

    public function scopeAutenticar($query, $email, $cpf, $ano)
    {
        return $query->where('email_autor', $email)
                     ->where('cpf_rg', $cpf)
                     ->whereHas('trabalho', function ($q) use ($ano) {
                         $q->where('ano', $ano);
                     });
    }

}
