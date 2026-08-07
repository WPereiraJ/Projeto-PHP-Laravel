<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ResumoExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $ano;
    protected $totalParticipantes;

    public function __construct($ano)
    {
        $this->ano = $ano;

        $this->totalParticipantes = DB::table('tb_inscricoes as i')
            ->join('trabalhos as t', 't.inscricao_id', '=', 'i.id')
            ->where('t.ano', $ano)
            ->count();
    }

    public function query()
    {
        return DB::table('trabalhos as t')
            ->join('tb_inscricoes as i', 't.inscricao_id', '=', 'i.id')
            ->where('t.ano', $this->ano)
            ->select('t.id as trabalho_id', 'i.*', 't.ano')
            ->distinct()
            ->orderBy('i.categoria')
            ->orderBy('i.nome_trabalho');
    }

    public function headings(): array
    {
        return [
            'Nome do Trabalho',
            'Nome do Autor',
            'CPF/RG',
            'Afiliação do Autor',
            'Email do Autor',
            'Nome do Autor 2',
            'Afiliação do Autor 2',
            'Nome do Autor 3',
            'Afiliação do Autor 3',
            'Nome do Autor 4',
            'Afiliação do Autor 4',
            'Nome do Autor 5',
            'Afiliação do Autor 5',
            'Nome do Autor 6',
            'Afiliação do Autor 6',
            'Nome do Autor 7',
            'Afiliação do Autor 7',
            'Nome do Autor 8',
            'Afiliação do Autor 8',
            'Nome do Autor 9',
            'Afiliação do Autor 9',
            'Nome do Autor 10',
            'Afiliação do Autor 10',
            'Resumo',
            'Termos de Indexação',
            'Financiamento',
            'SEG/Contrato',
            'Instituição',
            'Categoria',
            'Email do Orientador',
            'SISGEN',
            'Avaliação (Avaliador | R | I | A | D)',
            'Ano',
            'Total de Participantes',
            'Comentários da Comissão'
        ];
    }

    public function map($row): array
    {
        // --- BUSCA DAS 4 NOTAS ---
        $avaliacoes = DB::table('avaliador_trabalhos as at')
            ->join('tb_usuarios as u', 'at.usuario_id', '=', 'u.id')
            ->where('at.trabalhos_id', $row->trabalho_id)
            ->select(
                'u.nome',
                'at.resumo',
                'at.importancia',
                'at.apresentacao',
                'at.desempenho'
            )
            ->get();

        // Formatação da célula: Nome (R: 0, I: 0, A: 0, D: 0)
        $textoAvaliacao = $avaliacoes->map(function ($aval) {
            $r = $aval->resumo ?? 0;
            $i = $aval->importancia ?? 0;
            $a = $aval->apresentacao ?? 0;
            $d = $aval->desempenho ?? 0;

            return "{$aval->nome} (R:{$r}, I:{$i}, A:{$a}, D:{$d})";
        })->implode(' | ');

        // Busca Comentários
        $comentarios = DB::table('tb_comissao as c')
            ->join('tb_usuarios as u', 'c.usuario_id', '=', 'u.id')
            ->where('c.trabalho_id', $row->trabalho_id)
            ->select('c.comentarios', 'u.nome as autor_comentario')
            ->get();

        $comentariosTexto = $comentarios->map(function ($c) {
            return "{$c->autor_comentario}: {$c->comentarios}";
        })->implode(' | ');

        return [
            $row->nome_trabalho,
            $row->nome_autor,
            $row->cpf_rg,
            $row->afiliacao_autor,
            $row->email_autor,
            $row->nome_autor2,
            $row->afiliacao_autor2,
            $row->nome_autor3,
            $row->afiliacao_autor3,
            $row->nome_autor4,
            $row->afiliacao_autor4,
            $row->nome_autor5,
            $row->afiliacao_autor5,
            $row->nome_autor6,
            $row->afiliacao_autor6,
            $row->nome_autor7,
            $row->afiliacao_autor7,
            $row->nome_autor8,
            $row->afiliacao_autor8,
            $row->nome_autor9,
            $row->afiliacao_autor9,
            $row->nome_autor10,
            $row->afiliacao_autor10,
            $row->resumo,
            $row->termos_indexacao,
            $row->financiamento,
            $row->seg_contrato,
            $row->instituicao,
            $row->categoria,
            $row->email_orientador,
            $row->sisgen,
            $textoAvaliacao,
            $row->ano,
            $this->totalParticipantes,
            $comentariosTexto
        ];
    }
}