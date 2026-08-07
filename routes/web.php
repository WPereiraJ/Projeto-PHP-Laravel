<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApresentacaoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ResumoController;
use App\Http\Controllers\VotacaoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CentralGestaoController;
use App\Http\Controllers\PermissaoController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\NotasController;
use App\Http\Controllers\NotasAvaliadoresController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PesquisaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rotas Públicas / Iniciais ---
Route::prefix('/')->group(function () {
    // Tela inicial
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::view('/empregados', 'main.admin')->name('empregados');
    Route::view('login', 'main.login')->name('login');


    // Login externo e comissão
    Route::post('login/externo', [LoginController::class, 'loginExterno'])->name('login.externo');
    Route::post('login/comissao', [LoginController::class, 'loginComissao'])->name('login.comissao');
});

// --- Rotas Autenticadas / App ---
// Agrupando middleware 'perm.pagina'
Route::middleware(['perm.pagina'])->group(function () {

    // Apresentações
    Route::prefix('/apresentacoes')->group(function () {
        Route::get('/', [ApresentacaoController::class, 'index'])->name('app.apresentacoes');
        Route::post('/votacao', [VotacaoController::class, 'store'])->name('app.apresentacao.votacao');
    });

    // Resumos
    Route::prefix('/resumos')->group(function () {
        Route::get('/', [ResumoController::class, 'index'])->name('app.resumos');
        Route::post('/resumos/avaliar', [ResumoController::class, 'avaliar'])->name('app.resumos.avaliar');
    });

    // Central de Gestão
    Route::get('/central-de-gestao', [CentralGestaoController::class, 'index'])->name('app.central-de-gestao');
    Route::post('/arquivos/resumo-pdf', [CentralGestaoController::class, 'downloadResumo'])->name('app.arquivos.resumo-pdf');


    // Rotas de Usuário
    Route::prefix('usuarios')->group(function () {
        Route::post('/store', [UserController::class, 'store'])->name('usuarios.store');
        Route::put('/update', [UserController::class, 'update'])->name('usuarios.update');
        Route::post('/toggle', [UserController::class, 'toggleStatus'])->name('usuarios.toggle');
    });

    // Gestão de Permissões
    Route::post('/permissoes/update', [PermissaoController::class, 'update'])->name('app.permissoes.update');
    // Rota para gravar a escolha do usuário na sessão
    Route::post('/central-de-gestao/selecionar', [CentralGestaoController::class, 'selecionarUsuario'])
        ->name('app.central-gestao.selecionar');

    // Datas e Vínculos
    Route::post('/prazos/update', [CentralGestaoController::class, 'updatePrazos'])->name('app.prazos.update');

    // Vincular Trabalhos
    Route::post('/vincular/update', [CentralGestaoController::class, 'vincularTrabalhos'])->name('app.vincular.update');

    // Download de Arquivos
    Route::post('/arquivos/resumo', [CentralGestaoController::class, 'exportarResumo'])->name('app.arquivos.resumo');
    Route::post('/arquivos/inscritos', [CentralGestaoController::class, 'exportarInscritos'])->name('app.arquivos.inscritos');
    Route::post('/arquivos/apresentacao', [CentralGestaoController::class, 'downloadApresentacao'])->name('app.arquivos.apresentacao');

    // Termos
    Route::post('/central-gestao/termos', [CentralGestaoController::class, 'salvarTermos'])->name('app.termos.salvar');

    // Perfil
    Route::prefix('/perfil')->group(function () {
        Route::get('/', [PerfilController::class, 'index'])->name('app.perfil');
        Route::post('/update', [PerfilController::class, 'update'])->name('app.perfil.update');
    });

    // --- Rotas de Avaliação e Notas ---

    // Notas Avaliadores
    Route::get('/notas-avaliadores', [NotasAvaliadoresController::class, 'index'])->name('app.notas-avaliadores');

    // Notas Gerais
    Route::get('/notas-gerais', [NotasController::class, 'index'])->name('app.notas-gerais');

    // Verificar Inscrições
    Route::prefix('/verificar-inscricoes')->group(function () {
        Route::get('/', [ArquivoController::class, 'index'])->name('app.verificar-inscricoes');
        Route::post('/arquivos', [ArquivoController::class, 'store'])->name('app.arquivos.store');
        Route::get('/arquivos/{id}/download', [ArquivoController::class, 'download'])->name('app.arquivos.download');

    });

    // Votações
    Route::get('/votacoes', [VotacaoController::class, 'resultados'])->name('app.votacoes');

});

// --- Rotas de Fluxos (Cadastro e Pesquisa) ---

// Cadastro de Pesquisa
Route::get('/inscricao', [PesquisaController::class, 'create'])->name('inscricao');
Route::post('/inscricao', [PesquisaController::class, 'store'])->name('inscricao.store');
Route::view('/inscricao/sucesso', 'main.cadastro-sucesso')->name('inscricao.sucesso');

// Login Pesquisas
Route::get('/login-pesquisa', [PesquisaController::class, 'index'])->name('main.login-pesquisas');
Route::post('/login-pesquisa', [PesquisaController::class, 'login'])->name('main.login.submit');
Route::post('/logout-pesquisa', [PesquisaController::class, 'logout'])->name('pesquisa.logout');


// Painel de Pesquisa
Route::prefix('painel-pesquisador')->group(function () {
    // Visualizar Dashboard
    Route::get('/', [PesquisaController::class, 'dashboard'])->name('pesquisa.dashboard');
    
    // Atualizar Dados
    Route::put('/atualizar', [PesquisaController::class, 'update'])->name('pesquisa.update');
    
    // Enviar Arquivo
    Route::post('/upload-arquivo', [PesquisaController::class, 'uploadArquivo'])->name('pesquisa.upload');
});

// Home Pesquisas
Route::view('/home-pesquisas', 'app.home-pesquisas')->name('app.home-pesquisas');
Route::post('/home-pesquisas', function () {
    return view('app.home-pesquisas');
});

// Acesso Negado
Route::view('/acesso-negado', 'app.acesso-negado')->name('app.acesso-negado');