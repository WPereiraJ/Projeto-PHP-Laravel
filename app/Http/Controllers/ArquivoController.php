<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arquivo;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class ArquivoController extends Controller
{
    public function index()
    {
        $anoAtual = date('Y');

        // Seleciona apenas ID e Nome
        $arquivos = Arquivo::select('id', 'nome_arquivo')
            ->where('ano', $anoAtual)
            ->orderBy('nome_arquivo')
            ->get();

        return view('app.verificar-inscricoes', compact('arquivos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Valida se é PDF e até 10MB
            'arquivo' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $file = $request->file('arquivo');
            $conteudo = file_get_contents($file->getRealPath());
            if (DB::connection()->getDriverName() === 'pgsql') {
                $conteudo = '\\x' . bin2hex($conteudo);
            }

            $nomeFinal = $this->gerarNomeUnico();
            $anoAtual = date('Y');

            Arquivo::create([
                'nome_arquivo' => $nomeFinal,
                'arquivo' => $conteudo,
                'ano' => $anoAtual
            ]);

            return redirect()->back()->with('success', 'Arquivo enviado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Houve um erro ao salvar (Verifique o tamanho ou formato).');
        }
    }

    public function download($id)
    {
        $arquivo = Arquivo::find($id);

        if (!$arquivo) {
            return redirect()->back()->with('error', 'Arquivo não encontrado.');
        }

        $conteudo = $arquivo->arquivo;

        // 3. TRATAMENTO DE SAÍDA (STREAM):
        if (is_resource($conteudo)) {
            $conteudo = stream_get_contents($conteudo);
        }

        // Se por algum motivo veio como hex string (sem decode automático), converte de volta
        // (Geralmente não precisa no Laravel moderno, mas é uma segurança extra)
        if (is_string($conteudo) && strpos($conteudo, '\\x') === 0) {
            $conteudo = hex2bin(substr($conteudo, 2));
        }

        return Response::make($conteudo, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $arquivo->nome_arquivo . '"'
        ]);
    }

    private function gerarNomeUnico()
    {
        $ano = date('Y');
        $baseNome = "pibic_pibit_{$ano}";
        $extensao = ".pdf";

        $contador = 0;
        $nomeGerado = "{$baseNome}{$extensao}";

        while (Arquivo::where('nome_arquivo', $nomeGerado)->exists()) {
            $contador++;
            $nomeGerado = "{$baseNome}({$contador}){$extensao}";
        }

        return $nomeGerado;
    }
}