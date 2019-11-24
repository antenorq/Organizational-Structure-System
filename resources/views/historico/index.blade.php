@extends('layouts.app')

@section('content')
	<div class="area_opcoes">
		<br><br>         
        <a href="historico/orgao"><div class="area_botao"><img src="{{ asset('images/icon_orgao.png') }}"/><br/>ÓRGÃO</div></a> 
        <a href="historico/unidade"><div class="area_botao"><img src="{{ asset('images/icon_orgao.png') }}"/><br/>UNIDADE</div></a> 
        <a href="historico/orgao-colegiado"><div class="area_botao"><img src="{{ asset('images/icon_orgao.png') }}"/><br/>ÓRGÃO COLEGIADO</div></a> 
        <a href="historico/ato-normativo"><div class="area_botao"><img src="{{ asset('images/icon_acompanhamento.png') }}"/><br/>ATO NORMATIVO</div></a> 
		<div class="clear"></div>
        <br><br>   
	</div>
@endsection