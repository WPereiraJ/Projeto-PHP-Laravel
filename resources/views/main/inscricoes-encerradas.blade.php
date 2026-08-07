@extends('main.layouts.main-auth')

@section('titulo', 'Inscrições Encerradas')

@section('conteudo')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <div class="text-center bg-white p-5 rounded shadow-sm border-top border-warning"
                    style="border-top-width: 5px !important; margin-top: 50px;">

                    {{-- Ícone --}}
                    <div class="mb-4">
                        <span class="fa-stack fa-3x text-warning">
                            <i class="fas fa-circle fa-stack-2x" style="opacity: 0.2"></i>
                            <i class="fas fa-file-signature fa-stack-1x"></i> {{-- Ícone de assinatura/inscrição --}}
                        </span>
                    </div>

                    <h3 class="font-weight-bold text-dark mb-3">Inscrições Indisponíveis</h3>

                    <p class="text-muted mb-4">
                        O formulário de submissão de pesquisas
                        <strong>não está ativo</strong> no momento.
                    </p>

                    {{-- Mostra o período correto se as datas existirem --}}
                    @if(isset($datas) && !empty($datas->data_inicio_inscricoes))
                        <div class="alert alert-light border mb-4">
                            <small class="text-uppercase text-muted font-weight-bold">Período de Inscrições</small>
                            <h5 class="mt-2 mb-0 text-dark">
                                {{ \Carbon\Carbon::parse($datas->data_inicio_inscricoes)->format('d/m/Y') }}
                                até
                                {{ \Carbon\Carbon::parse($datas->data_final_inscricoes)->format('d/m/Y') }}
                            </h5>
                        </div>
                    @else
                        <div class="alert alert-light border mb-4">
                            <p class="mb-0 text-muted">Datas ainda não definidas pela organização.</p>
                        </div>
                    @endif

                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-block font-weight-bold">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar para o Início
                    </a>

                </div>

            </div>
        </div>
    </div>
@endsection