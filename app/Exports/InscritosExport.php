<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InscritosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $ano;

    public function __construct($ano)
    {
        $this->ano = $ano;
    }

    public function query()
    {
        return DB::table('tb_inscricoes as i')
            ->join('trabalhos as t', 't.inscricao_id', '=', 'i.id')
            ->where('t.ano', $this->ano)
            ->select(
                'i.nome_autor',
                'i.nome_trabalho',
                'i.categoria',
                'i.resumo',
                'i.cpf_rg',
                'i.email_autor',
                'i.afiliacao_autor',
                'i.seg_contrato',
                'i.instituicao',
                'i.orientador',
                'i.sisgen'
            )
            ->orderBy('i.nome_autor');
    }

    public function headings(): array
    {
        return [
            'Nome do Participante',
            'Nome da Pesquisa',
            'Categoria',
            'Resumo',
            'CPF/RG',
            'E-mail',
            'Afiliação',
            'Contrato',
            'Instituição',
            'Orientador',
            'SiSGEN'
        ];
    }

    public function map($row): array
    {
        // O trim() limpa espaços vazios antes e depois
        return [
            trim($row->nome_autor),
            trim($row->nome_trabalho),
            trim($row->categoria),
            trim($row->resumo),
            trim($row->cpf_rg),
            trim($row->email_autor),
            trim($row->afiliacao_autor),
            trim($row->seg_contrato),
            trim($row->instituicao),
            trim($row->orientador),
            trim($row->sisgen)
        ];
    }
}