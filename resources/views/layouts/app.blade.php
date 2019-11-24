<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <link href="{{ asset('css/topo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/rodape.css') }}" rel="stylesheet">
    <link href="{{ asset('js/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.13/datatables.min.css"/>
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="area_body">
    @include('layouts.topo')
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <!-- <a class="navbar-brand" href="{{ url('/') }}">INÍCIO</a>-->
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-left">
                        <li @if (Route::currentRouteName() == 'home') class="menu_ativo" @endif ><a href="{{ url('/home') }}">INÍCIO</a></li>

                        @if (Auth::check())
                            <li @if (Request::is('orgao/*') || Request::is('orgao'))  class="menu_ativo" @endif ><a href="{{ url('/orgao') }}">ÓRGÃO</a></li>
                            <li @if (Request::is('unidade/*') || Request::is('unidade'))  class="menu_ativo" @endif ><a href="{{ url('/unidade') }}">UNIDADE</a></li>
                            <li @if (Request::is('orgaocolegiado/*') || Request::is('orgaocolegiado'))  class="menu_ativo" @endif ><a href="{{ url('/orgaocolegiado') }}">ÓRGÃO COLEGIADO</a></li>
                            <li @if (Request::is('cargo/*') || Request::is('cargo'))  class="menu_ativo" @endif ><a href="{{ url('/cargo') }}">CARGO E FUNÇÃO</a></li>
                            <li @if (Request::is('atonormativo/*') || Request::is('atonormativo'))  class="menu_ativo" @endif ><a href="{{ url('/atonormativo') }}">ATO NORMATIVO</a></li>
                            <li @if (Request::is('equipe/*') || Request::is('equipe'))  class="menu_ativo" @endif ><a href="{{ url('/equipe') }}">EQUIPE</a></li>
                         @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <!--<li><a href="{{ route('login') }}">Login</a></li>-->
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('usuario.dados-usuario') }}">
                                            Alterar dados
                                        </a>

                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>                                       

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <!-- AREA CONTENT ONDE CARREGA AS VIEWS DO LARAVEL -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">

                        <!-- EXIBE OS ERROS -->
                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <!-- EXIBE MENSAGEM DE SUCESSO -->
                        @if(session()->has('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <!-- EXIBE MENSAGEM DE ERRO -->
                        @if(session()->has('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <!-- EXIBE CONTEÚDO PADRÃO QUE ESTÁ NA SECTION NAs VIEWs -->
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('layouts.rodape')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" charset="UTF-8"></script>
    <script src="{{ asset('js/deleteGrid.js') }}"></script>
    <script src="{{ asset('js/maskedinput.js') }}"></script>
    <script src="{{ asset('js/helpers.js') }}"></script>
    <script src="{{ asset('js/historico.js') }}"></script>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.13/datatables.min.js"></script>
    <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
    @yield('js')
    <script>
        (function(){
            if($("#gestor").length > 0){
                getUnidadesDoOrgao($("#gestor"));
            }
		})();
    </script>
</body>
</html>
