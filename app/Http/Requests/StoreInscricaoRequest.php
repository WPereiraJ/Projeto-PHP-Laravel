<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StoreInscricaoRequest extends FormRequest
{
    /**
     * Lógica de Autorização e Segurança
     * Aqui verificamos se as inscrições estão abertas.
     */
    public function authorize()
    {
        $anoAtual = date('Y');

        // 1. Busca as datas no banco
        $datas = DB::table('tb_datas_evento')
            ->whereYear('data_inicio_evento', $anoAtual)
            ->first();

        // 2. Se não houver datas configuradas, bloqueia.
        if (!$datas || empty($datas->data_inicio_inscricoes) || empty($datas->data_final_inscricoes)) {
            return false;
        }

        // 3. Verifica se HOJE está dentro do prazo
        $agora = Carbon::now();
        $inicio = Carbon::parse($datas->data_inicio_inscricoes)->startOfDay();
        $fim = Carbon::parse($datas->data_final_inscricoes)->endOfDay();            

        // Se estiver fora do prazo, retorna false
        if ($agora->lt($inicio) || $agora->gt($fim)) {
            return false;
        }

        return true; // Autorizado
    }

    /**
     * Regras de Validação dos Campos
     */
    public function rules()
    {
        $anoAtual = date('Y');

        return [
            'nome_trabalho' => 'required|string|max:255',
            'financiamento' => 'required|string|max:255',
            'seg_contrato' => 'required|string|max:100',
            'sisgen' => 'nullable|string|max:100',

            // Dados do Autor
            'nome_autor' => 'required|string|max:255',
            'afiliacao_autor' => 'required|string|max:255',
            'orientador' => 'required|string|max:255',
            'email_orientador' => 'required|email|max:255',
            'category' => 'required|string',

            // Validação de Email Único no Ano
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($anoAtual) {
                    $existe = DB::table('tb_inscricoes as i')
                        ->join('trabalhos as t', 'i.id', '=', 't.inscricao_id')
                        ->where('i.email_autor', $value)
                        ->where('t.ano', $anoAtual)
                        ->exists();
                    if ($existe)
                        $fail('Este e-mail já realizou uma submissão este ano.');
                }
            ],

            // --- VALIDAÇÃO HÍBRIDA: CPF (Matemático) ou RG (Genérico) ---
            'cpf_rg' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($anoAtual) {
                    // 1. Limpeza (apenas números)
                    $cpfLimpo = preg_replace('/[^0-9]/', '', $value);

                    // 2. Decisão: É CPF ou RG?
                    if (strlen($cpfLimpo) === 11) {
                        // Se tem 11 dígitos, TRATA COMO CPF e valida matematicamente
                        if (!$this->isValidCpf($cpfLimpo)) {
                            $fail('O CPF informado é inválido.');
                            return;
                        }
                    } else {
                        // Se NÃO tem 11 dígitos, TRATA COMO RG
                        // Apenas verificamos se não é muito curto (ex: erro de digitação "123")
                        if (strlen($cpfLimpo) < 5) {
                            $fail('O documento (RG) informado parece inválido.');
                            return;
                        }
                    }

                    // 3. Verifica Duplicidade no Banco
                    $existe = DB::table('tb_inscricoes as i')
                        ->join('trabalhos as t', 'i.id', '=', 't.inscricao_id')
                        ->where('i.cpf_rg', $value)
                        ->where('t.ano', $anoAtual)
                        ->exists();

                    if ($existe)
                        $fail('Este CPF/RG já possui uma inscrição neste ano.');
                }
            ],

            // Termos e Resumo
            'termos' => 'accepted',
            'resumo' => 'required|string|min:50',
            'termosIndexacao' => 'nullable|string',

            // Co-autores
            'nome_autor*' => 'nullable|string|max:255',
            'afiliacao_autor*' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'termos.accepted' => 'Você precisa ler e aceitar os termos e condições.',
            'resumo.required' => 'O resumo do trabalho é obrigatório.',
            'resumo.min' => 'O resumo deve ter pelo menos 50 caracteres.',
        ];
    }

    /**
     * Função auxiliar para validar CPF matematicamente
     */
    private function isValidCpf($cpf)
    {
        // Verifica se todos os dígitos são iguais (ex: 111.111.111-11)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação matemática
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}