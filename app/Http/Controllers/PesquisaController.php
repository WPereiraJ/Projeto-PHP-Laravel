<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreInscricaoRequest;
use App\Models\Inscricao;
use App\Models\Trabalho;
use App\Models\TermoInstrucao;
use App\Models\ArquivoResumo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Requests\LoginPesquisaRequest;
use Illuminate\Support\Facades\Session;

class PesquisaController extends Controller
{

    public function index()
    {
        // Retorna a view com o formulário de login
        return view('main.login-pesquisas');
    }

    /**
     * Exibe o formulário de cadastro (Lógica ajustada anteriormente)
     */
    public function create()
    {
        $anoAtual = date('Y');
        $agora = Carbon::now();

        // 1. Busca evento DESTE ANO que esteja com inscrições abertas HOJE
        $eventoAtivo = DB::table('tb_datas_evento')
            ->whereYear('data_inicio_evento', $anoAtual)
            ->where('data_inicio_inscricoes', '<=', $agora)
            ->where('data_final_inscricoes', '>=', $agora)
            ->first();

        // 2. Se achou, libera o cadastro
        if ($eventoAtivo) {
            $termos = TermoInstrucao::orderBy('id', 'desc')->first();
            return view('main.cadastro', compact('termos'));
        }

        // 3. Se não, mostra tela de encerrado com as datas do evento deste ano
        $datas = DB::table('tb_datas_evento')
            ->whereYear('data_inicio_evento', $anoAtual)
            ->first();

        return view('main.inscricoes-encerradas', compact('datas'));
    }

    /**
     * Processa o salvamento da inscrição
     */
    public function store(StoreInscricaoRequest $request)
    {
        // Pega apenas os dados que passaram nas regras do StoreInscricaoRequest
        $dados = $request->validated();

        try {
            DB::beginTransaction();

            // 1. Prepara o array para criar a Inscrição
            $inscricaoData = [
                'nome_autor' => $dados['nome_autor'],
                'nome_trabalho' => $dados['nome_trabalho'],
                'afiliacao_autor' => $dados['afiliacao_autor'],
                'orientador' => $dados['orientador'],
                'email_autor' => $dados['email'], // Form: email -> Banco: email_autor
                'email_orientador' => $dados['email_orientador'],
                'cpf_rg' => $dados['cpf_rg'],
                'seg_contrato' => $dados['seg_contrato'],
                'financiamento' => $dados['financiamento'],
                'categoria' => $dados['category'], // Form: category -> Banco: categoria
                'resumo' => $dados['resumo'],
                'termos_indexacao' => $dados['termosIndexacao'] ?? null,
                'sisgen' => $dados['sisgen'] ?? null,
                'termos' => true,
            ];

            // 2. Adiciona os Co-autores dinamicamente (2 ao 10)
            for ($i = 2; $i <= 10; $i++) {
                // Se o nome do autor X foi enviado, adiciona ele e a afiliação ao array
                if (!empty($dados["nome_autor$i"])) {
                    $inscricaoData["nome_autor$i"] = $dados["nome_autor$i"];
                    $inscricaoData["afiliacao_autor$i"] = $dados["afiliacao_autor$i"] ?? null;
                }
            }

            // 3. Salva a Inscrição na tabela tb_inscricoes
            $inscricao = Inscricao::create($inscricaoData);

            // 4. Cria o vínculo na tabela trabalhos
            Trabalho::create([
                'inscricao_id' => $inscricao->id,
                'ano' => date('Y')
            ]);

            DB::commit();

            // 5. Redireciona para a página de sucesso (Loading Page)
            return redirect()->route('inscricao.sucesso');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput() // Mantém os dados preenchidos
                ->with('error', 'Ocorreu um erro ao processar sua inscrição. Tente novamente.');
        }
    }

    public function login(LoginPesquisaRequest $request)
    {
        // 1. Limpeza básica dos dados
        $email = trim($request->email);
        $cpf = trim($request->cpf); // Mantem como string pois pode ser RG com letras
        $anoAtual = date('Y');

        // 2. Busca usando o Escopo
        $pesquisador = Inscricao::autenticar($email, $cpf, $anoAtual)->first();

        // 3. Verificação
        if ($pesquisador) {
            // Login Sucesso: Salva dados na Sessão
            // Usa nomes claros para não confundir com o Auth::user() padrão
            Session::put('pesquisador', [
                'id' => $pesquisador->id,
                'nome' => $pesquisador->nome_autor,
                'email' => $pesquisador->email_autor,
                'ano' => $anoAtual
            ]);

            return redirect()->route('pesquisa.dashboard');
        }

        // 4. Login Falhou
        return redirect()->back()
            ->withInput($request->only('email')) // Mantém o email preenchido
            ->with('erro', 'Credenciais inválidas ou nenhuma inscrição encontrada para este ano.');
    }

    /**
     * Logout
     */
    public function logout()
    {
        Session::forget('pesquisador');
        return redirect()->route('main.login-pesquisas');
    }
    public function dashboard()
    {
        if (!Session::has('pesquisador')) {
            return redirect()->route('main.login-pesquisas')->with('erro', 'Faça login primeiro.');
        }

        $pesquisadorSessao = Session::get('pesquisador');
        $inscricao = Inscricao::with('trabalho')->find($pesquisadorSessao['id']);

        $anoAtual = date('Y');
        $datasEvento = DB::table('tb_datas_evento')->whereYear('data_inicio_evento', $anoAtual)->first();

        $agora = Carbon::now();
        $edicaoPermitida = false;

        if ($datasEvento && $datasEvento->data_inicio_arquivo && $datasEvento->data_final_arquivo) {
            $edicaoPermitida = $agora->between(
                Carbon::parse($datasEvento->data_inicio_arquivo)->startOfDay(),
                Carbon::parse($datasEvento->data_final_arquivo)->endOfDay()
            );
        }

        $historicoArquivos = ArquivoResumo::select('id', 'tipo', 'nome_arquivo', 'created_at')
            ->where('inscricao_id', $inscricao->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // === BUSCA DE COMENTÁRIOS ===
        $comentarios = collect(); 
        
        $trabalho = DB::table('trabalhos')->where('inscricao_id', $inscricao->id)->first();

        if ($trabalho) {
            $comentarios = DB::table('tb_comissao')
                ->join('tb_usuarios', 'tb_comissao.usuario_id', '=', 'tb_usuarios.id')
                ->where('tb_comissao.trabalho_id', $trabalho->id)
                ->whereNotNull('tb_comissao.comentarios')
                ->where('tb_comissao.comentarios', '!=', '')
                ->select(
                    'tb_usuarios.nome as avaliador_nome', 
                    'tb_comissao.comentarios as texto'
                )
                ->get();
        }

        // === LÓGICA DE NOTAS ===
        $notaResumo = '--';
        $notaApresentacao = '--';
        $notaImportancia = '--';
        $notaDesempenho = '--';

        // Se o trabalho já foi criado na tabela trabalhos
        if ($inscricao && $inscricao->trabalho) {
            // Busca todas as avaliações deste trabalho
            $avaliacoes = DB::table('avaliador_trabalhos')
                ->where('trabalhos_id', $inscricao->trabalho->id)
                ->get();

            // Se existir pelo menos uma avaliação preenchida, calcula a média
            if ($avaliacoes->count() > 0) {
                $notaResumo = number_format($avaliacoes->avg('resumo'), 2, ',', '.');
                $notaApresentacao = number_format($avaliacoes->avg('apresentacao'), 2, ',', '.');
                $notaImportancia = number_format($avaliacoes->avg('importancia'), 2, ',', '.');
                $notaDesempenho = number_format($avaliacoes->avg('desempenho'), 2, ',', '.');
            }
        }
        // ============================

        return view('main.dashboard', compact(
            'inscricao',
            'datasEvento',
            'edicaoPermitida',
            'historicoArquivos',
            'notaResumo',
            'notaApresentacao',
            'notaImportancia',
            'notaDesempenho',
            'comentarios',
        ));
    }

    public function update(Request $request)
    {
        if (!Session::has('pesquisador'))
            return redirect()->route('main.login-pesquisas');

        $pesquisadorSessao = Session::get('pesquisador');
        $inscricao = Inscricao::find($pesquisadorSessao['id']);

        $anoAtual = date('Y');

        // === BLOQUEIO DE PRAZO ===
        $datasEvento = DB::table('tb_datas_evento')->whereYear('data_inicio_evento', $anoAtual)->first();
        $agora = Carbon::now();

        if (!$datasEvento || !$datasEvento->data_inicio_arquivo || !$datasEvento->data_final_arquivo || !$agora->between(Carbon::parse($datasEvento->data_inicio_arquivo)->startOfDay(), Carbon::parse($datasEvento->data_final_arquivo)->endOfDay())) {
            return redirect()->route('pesquisa.dashboard', '#v-pills-editar')
                ->with('erro', 'O prazo para edição de dados já encerrou ou ainda não começou.');
        }
        // =================================================

        // Validação dos Dados
        $request->validate([
            'seg_contrato' => 'nullable|string|max:255',
            'sisgen' => 'nullable|string|max:255',
            'resumo' => 'required|string|max:1650', 
            'termos_indexacao' => 'required|string|max:255',
        ], [
            'resumo.max' => 'O resumo não pode ultrapassar 1650 caracteres.',
            'resumo.required' => 'O resumo é obrigatório.',
            'termos_indexacao.required' => 'Os termos de indexação são obrigatórios.'
        ]);

        try {
            // Atualiza os dados da inscrição
            $inscricao->seg_contrato = $request->seg_contrato;
            $inscricao->sisgen = $request->sisgen;
            $inscricao->resumo = $request->resumo;
            $inscricao->termos_indexacao = $request->termos_indexacao;
            $inscricao->save();

            return redirect()->route('pesquisa.dashboard', '#v-pills-editar')
                ->with('success', 'Dados da pesquisa atualizados com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('erro', 'Erro ao atualizar os dados. Tente novamente.');
        }
    }

    public function uploadArquivo(Request $request)
    {
        if (!Session::has('pesquisador')) return redirect()->route('main.login-pesquisas');

        $pesquisadorSessao = Session::get('pesquisador');
        $inscricao = Inscricao::find($pesquisadorSessao['id']);

        $request->validate([
            'arquivo' => 'required|mimes:pdf|max:5120',
            'tipo_arquivo' => 'required|in:resumo,apresentacao'
        ], [
            'arquivo.required' => 'Por favor, selecione um arquivo.',
            'arquivo.mimes' => 'O arquivo deve ser um PDF.',
            'arquivo.max' => 'O tamanho máximo permitido é 5MB.'
        ]);

        $anoAtual = date('Y');
        $datasEvento = DB::table('tb_datas_evento')->whereYear('data_inicio_evento', $anoAtual)->first();
        $agora = Carbon::now();

        if (!$datasEvento) {
            return redirect()->back()->with('erro', 'As datas do evento não estão configuradas.');
        }

        // === LÓGICA DE PRAZOS SEPARADOS ===
        if ($request->tipo_arquivo === 'resumo') {
            $dataInicio = $datasEvento->data_inicio_inscricoes;
            $dataFim = $datasEvento->data_final_inscricoes;
            $nomeDocumento = 'resumo';
        } else {
            $dataInicio = $datasEvento->data_inicio_arquivo;
            $dataFim = $datasEvento->data_final_arquivo;
            $nomeDocumento = 'apresentação';
        }

        // Verifica se a data atual está dentro do período liberado para o TIPO de arquivo selecionado
        if (!$dataInicio || !$dataFim || !$agora->between(Carbon::parse($dataInicio)->startOfDay(), Carbon::parse($dataFim)->endOfDay())) {
            return redirect()->back()->with('erro', "O prazo para envio de {$nomeDocumento} já encerrou ou ainda não começou.");
        }
        // ========================================

        try {
            $conteudo = '\x' . bin2hex(file_get_contents($request->file('arquivo')->getRealPath()));
            $nomeLimpo = preg_replace('/[^a-zA-Z0-9.\-_]/', '_', $request->file('arquivo')->getClientOriginalName());

            if ($request->tipo_arquivo === 'resumo') {
                // REGRA 1: Resumos vão direto para a tabela nova (COM O ANO)
                ArquivoResumo::create([
                    'inscricao_id' => $inscricao->id,
                    'ano' => $anoAtual,
                    'tipo' => 'resumo',
                    'nome_arquivo' => $nomeLimpo,
                    'arquivo' => $conteudo,
                ]);
            } else {
                // REGRA 2: Apresentação
                if (!empty($inscricao->arquivo) && !empty($inscricao->nome_arquivo)) {
                    ArquivoResumo::create([
                        'inscricao_id' => $inscricao->id,
                        'ano' => $anoAtual,
                        'tipo' => 'apresentacao',
                        'nome_arquivo' => $inscricao->nome_arquivo,
                        'arquivo' => $inscricao->arquivo,
                    ]);
                }

                // Salva a NOVA apresentação na tabela principal de inscrições
                $inscricao->arquivo = $conteudo;
                $inscricao->nome_arquivo = $nomeLimpo;
                $inscricao->save();
            }

            return redirect()->route('pesquisa.dashboard', '#v-pills-arquivos')
                ->with('success', 'Arquivo enviado com sucesso!');

        } catch (\Exception $e) {
            // Em caso de debug, remova o comentário abaixo
            // dd($e->getMessage());
            return redirect()->back()->with('erro', 'Erro ao processar o arquivo. Tente novamente.');
        }
    }
}