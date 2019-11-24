@extends('layouts.app')

@section('content')

    @if (session('status'))
            <div class="alert alert-danger">
            {{ session('status') }}
        </div>
    @endif

    <div id="area_opcoes">
        <br><br>
        <a href="orgao"><div class="area_botao"><img src="{{ asset('images/icon_orgao.png') }}"/><br/>ÓRGÃO</div></a>
        <a href="unidade"><div class="area_botao"><img src="{{ asset('images/icon_orgao.png') }}"/><br/>UNIDADE</div></a>
        <a href="orgaocolegiado"><div class="area_botao"><img src="{{ asset('images/icon_orgao.png') }}"/><br/>ÓRGÃO COLEGIADO</div></a>
        <a href="atonormativo"><div class="area_botao"><img src="{{ asset('images/icon_acompanhamento.png') }}"/><br/>ATO NORMATIVO</div></a>

        <div class="clear"></div>
        <br><br>

        <a href="cargo"><div class="area_botao"><img src="{{ asset('images/icon_acompanhamento.png') }}"/><br/>CARGO E FUNÇÃO</div></a>
        <a href="equipe"><div class="area_botao"><img src="{{ asset('images/icon_equipe.png') }}"/><br/>EQUIPE</div></a>
        
        @if(Auth::User()->id_perfil == 1)
            <a href="usuario"><div class="area_botao"><img src="{{ asset('images/icon_cadastro.png') }}"/><br/>USUÁRIOS</div></a> 
        @endif

        <a href="relatorio"><div class="area_botao"><img src="{{ asset('images/icon_tipo_documento.png') }}"/><br/>RELATÓRIOS</div></a>        

        <div class="clear"></div>
        <br><br>
    </div>

@endsection
