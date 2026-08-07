@extends('app.layouts.main-app')

@section('css_path', 'css/home-pesquisas.css')
@section('titulo', 'Pesquisa')
@section('conteudo')

    <main role="main" class="container-fluid mt-4">

        <div>
            @component('app.layouts._components.coluna-notas')
            @endcomponent

            <div class="col-md-4">
                @component('app.layouts._components.forms-del-pesquisa')
                @endcomponent
            </div>

            @push('scripts')
                @component('app.layouts._components.forms-up-arquivo')
                @endcomponent
                <script src="{{ asset('js/form-pesquisa.js') }}"></script>
            @endpush
            <div class="col-md-4 comentarios-section">
                @component('app.layouts._components.comentarios-coluna')
                @endcomponent
            </div>
        </div>

    </main>

@endsection
