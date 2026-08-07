<?php

namespace App\Http\Controllers;


use App\Exports\ResumoExport;
use App\Exports\InscritosExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Trabalho;
use App\Models\Usuario;
use App\Models\Pagina;
use App\Models\DatasEvento;
use App\Models\TermoInstrucao;
use App\Models\AcessoPagina;

class CentralGestaoController extends Controller
{
    public function index(Request $request)
    {

        $usuarios = Usuario::orderBy('nome')
            ->get();
        $usuariosLista = Usuario::where('ativo', 1)
            ->orderBy('nivel_de_acesso_user', 'desc')
            ->get();
        $usuariosAtivos = Usuario::where('ativo', 1)
            ->orderBy('nome')
            ->get();

        $paginas = Pagina::all();
        $termosData = TermoInstrucao::first();
        $prazos = DatasEvento::where('ano', date('Y'))->first();
        $trabalhos = Trabalho::select(
            'trabalhos.id',                // ID
            'tb_inscricoes.nome_trabalho', // Nome
            'tb_inscricoes.categoria',     // Categoria
            'trabalhos.status'             // Status
        )
            ->join('tb_inscricoes', 'trabalhos.inscricao_id', '=', 'tb_inscricoes.id')
            ->where('trabalhos.ano', date('Y'))
            ->orderBy('tb_inscricoes.nome_trabalho', 'asc')
            ->get();

        $apresentacoes = DB::table('tb_inscricoes as i')
            ->join('trabalhos as t', 'i.id', '=', 't.inscricao_id')
            ->select('i.nome_arquivo')
            ->where('t.ano', date('Y'))
            ->whereNotNull('i.nome_arquivo')
            ->where('i.nome_arquivo', '!=', '')
            ->orderBy('i.nome_arquivo')
            ->get();

        $usuarioSelecionado = null;
        $permissoesAtuais = collect([]);

        if (session()->has('usuario_gerenciado_id')) {
            $idSession = session('usuario_gerenciado_id');
            $usuarioSelecionado = Usuario::find($idSession);

            if ($usuarioSelecionado) {
                $permissoesAtuais = $usuarioSelecionado->acessoPaginas->keyBy('pagina_id');
            }
        }

        $resumos = DB::table('tb_arquivos_resumos')
            ->where('tipo', 'resumo')
            ->where('ano', date('Y')) // Pode tirar essa linha se quiser mostrar de todos os anos
            ->get();

        // -----------------------------------------------------

        return view('app.central-de-gestao', compact(
            'usuarios',
            'usuariosAtivos',
            'usuariosLista',
            'paginas',
            'prazos',
            'trabalhos',
            'usuarioSelecionado',
            'permissoesAtuais',
            'apresentacoes',
            'termosData'
        ));
    }

    public function selecionarUsuario(Request $request)
    {
        // 1. Pega o ID enviado pelo formulário
        $id = $request->input('usuario_id');

        // 2. Salva na sessão (ou limpa se for vazio)
        if ($id) {
            session(['usuario_gerenciado_id' => $id]); // Salva na memória
        } else {
            session()->forget('usuario_gerenciado_id'); // Limpa se desmarcou
        }

        // 3. Redireciona para a página principal
        return redirect()->route('app.central-de-gestao');
    }

    public function atualizarPermissoes(Request $request)
    {
        // 1. VERIFICAÇÃO DE SEGURANÇA (Presidente e Dev)
        $nivelLogado = session('perfil'); // Ou auth()->user()->nivel_de_acesso_user

        // Níveis permitidos: 3 (Presidente) e 5 (Desenvolvedor)
        if (!in_array($nivelLogado, [3, 5])) {
            return redirect()->back()
                ->with('error', 'Ação não autorizada. Apenas Presidentes e Desenvolvedores podem alterar permissões.');
        }

        // 2. Validação
        $request->validate([
            'usuario_id' => 'required|exists:tb_usuarios,id',
            'permissoes' => 'array'
        ]);

        $usuarioIdAlvo = $request->input('usuario_id');
        $permissoesEnviadas = $request->input('permissoes', []);

        // Loop pelas permissões enviadas
        foreach ($permissoesEnviadas as $paginaId => $dados) {

            $acessar = isset($dados['acessar']) ? 1 : 0;
            $incluir = isset($dados['incluir']) ? 1 : 0;
            $editar = isset($dados['editar']) ? 1 : 0;
            $excluir = isset($dados['excluir']) ? 1 : 0;

            AcessoPagina::updateOrCreate(
                [
                    'usuario_id' => $usuarioIdAlvo,
                    'pagina_id' => $paginaId
                ],
                [
                    'acessar' => $acessar,
                    'incluir' => $incluir,
                    'editar' => $editar,
                    'excluir' => $excluir
                ]
            );
        }

        return redirect()->back()->with('success', 'Permissões atualizadas com sucesso!');
    }

    public function updatePrazos(Request $request)
    {
        $anoReferencia = date('Y');
        $dados = $request->all();

        try {
            // Busca pelo ANO. Se achar, faz UPDATE. Se não achar, faz INSERT.
            DatasEvento::updateOrCreate(
                ['ano' => $anoReferencia],
                $dados
            );

            return redirect()->route('app.central-de-gestao')->with('success', 'Prazos de ' . $anoReferencia . ' configurados!');

        } catch (\Exception $e) {
            // Se der erro, ele vai avisar exatamente qual coluna falhou
            return redirect()->back()->withErrors(['error' => 'Erro ao salvar: ' . $e->getMessage()]);
        }
    }
    public function vincularTrabalhos(Request $request)
    {
        // Validações
        $userIds = $request->input('usuario_ids');
        $trabalhoIds = $request->input('trabalho_ids');

        // Dados do formulário
        $ehAvaliador = $request->has('avaliador');
        $dataApresentacao = $request->input('data_apresentacao');    // Vem do Blade
        $horarioApresentacao = $request->input('horario_apresentacao'); // Vem do Blade

        try {
            DB::beginTransaction();

            foreach ($userIds as $usuarioId) {
                // 1. Busca os dados do usuário para saber qual é o tipo/nível dele
                $usuario = DB::table('tb_usuarios')->where('id', $usuarioId)->first();

                if (!$usuario)
                    continue; // Prevenção de erro caso o usuário não exista

                foreach ($trabalhoIds as $trabalhoId) {

                    // 2. Lógica de Vínculo baseada no NÍVEL do usuário
                    if (in_array($usuario->nivel_de_acesso_user, [1, 2, 3])) {
                        // É Comissão (1), CLPI (2) ou Presidente (3) -> Vai para a tb_comissao
                        // Precisa puxar o inscricao_id pois a tb_comissao exige essa coluna
                        $trabalho = DB::table('trabalhos')->where('id', $trabalhoId)->first();

                        if ($trabalho) {
                            DB::table('tb_comissao')->updateOrInsert(
                                ['usuario_id' => $usuarioId, 'trabalho_id' => $trabalhoId], // singular
                                ['inscricao_id' => $trabalho->inscricao_id]
                            );
                        }
                    } else {
                        // É Avaliador (0) -> Vai para a avaliador_trabalhos
                        DB::table('avaliador_trabalhos')->updateOrInsert(
                            ['usuario_id' => $usuarioId, 'trabalhos_id' => $trabalhoId], // plural
                            [] // As notas iniciam vazias
                        );
                    }

                    // 3. Lógica da Data (Atualizar na tabela TRABALHOS apenas se a flag estiver marcada)
                    if ($ehAvaliador && ($dataApresentacao || $horarioApresentacao)) {
                        $dadosParaAtualizar = [];

                        if ($dataApresentacao) {
                            $dadosParaAtualizar['data_apresentacao'] = $dataApresentacao;
                        }
                        if ($horarioApresentacao) {
                            $dadosParaAtualizar['horario_apresentacao'] = $horarioApresentacao;
                        }

                        // Atualiza a tabela original do trabalho
                        DB::table('trabalhos')
                            ->where('id', $trabalhoId)
                            ->update($dadosParaAtualizar);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Vínculos realizados com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao vincular: ' . $e->getMessage());
        }
    }

    // 1. Exportar Resumo
    public function exportarResumo(Request $request)
    {
        $request->validate(['ano' => 'required|integer']);
        $ano = $request->input('ano');

        return Excel::download(new ResumoExport($ano), 'JT_informacoes' . $ano . '.csv');
    }

    // 2. Exportar Inscritos
    public function exportarInscritos(Request $request)
    {
        $request->validate(['ano' => 'required|integer']);
        $ano = $request->input('ano');

        return Excel::download(new InscritosExport($ano), 'inscricoes_' . $ano . '.csv');
    }

    // 3. Download Apresentação
    public function downloadApresentacao(Request $request)
    {
        $request->validate(['nome_arquivo' => 'required|string']);

        $arquivoData = DB::table('tb_inscricoes as i')
            ->join('trabalhos as t', 'i.id', '=', 't.inscricao_id')
            ->select('i.nome_arquivo', 'i.arquivo')
            ->where('i.nome_arquivo', $request->nome_arquivo)
            ->where('t.ano', date('Y'))
            ->first();

        if (!$arquivoData || empty($arquivoData->arquivo)) {
            return redirect()->back()->withErrors(['error' => 'Arquivo não encontrado ou fora do período permitido.']);
        }

        return response()->streamDownload(function () use ($arquivoData) {
            echo is_resource($arquivoData->arquivo)
                ? stream_get_contents($arquivoData->arquivo)
                : $arquivoData->arquivo;
        }, $arquivoData->nome_arquivo, ['Content-Type' => 'application/pdf']);
    }

    public function downloadResumo(Request $request)
    {
        $request->validate(['nome_arquivo' => 'required|string']);

        $arquivoData = DB::table('tb_arquivos_resumos')
            ->select('nome_arquivo', 'arquivo')
            ->where('nome_arquivo', $request->nome_arquivo)
            ->where('tipo', 'resumo')
            ->first();

        if (!$arquivoData || empty($arquivoData->arquivo)) {
            return redirect()->back()->with('error', 'Arquivo de resumo não encontrado.');
        }

        return response()->streamDownload(function () use ($arquivoData) {
            echo is_resource($arquivoData->arquivo)
                ? stream_get_contents($arquivoData->arquivo)
                : $arquivoData->arquivo;
        }, $arquivoData->nome_arquivo, ['Content-Type' => 'application/pdf']);
    }

    public function salvarTermos(Request $request)
    {
        $request->validate([
            'termos' => 'required',
            'instrucoes' => 'required'
        ]);

        TermoInstrucao::updateOrCreate(
            ['id' => 1],
            [
                'termos' => $request->termos,
                'instrucoes' => $request->instrucoes
            ]
        );

        return redirect()->back()->with('success', 'Termos e instruções atualizados com sucesso!');
    }

}

