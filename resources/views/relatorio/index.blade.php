@extends('layouts.app')

@section('content')
	<div class="area_opcoes">
		<br><br>         
        <a href="hierarquia"><div class="area_botao"><img src="{{ asset('images/icon_setor.png') }}"/><br/>HIERARQUIA</div></a> 
        <a href="historico"><div class="area_botao"><img src="{{ asset('images/unid.png') }}"/><br/>HISTÓRICO</div></a>
        <a href="organograma"><div class="area_botao"><img src="{{ asset('images/organograma.png') }}"/><br/>ORGANOGRAMA</div></a>
        <a href="cadastro-organizacional"><div class="area_botao"><img src="{{ asset('images/documento_orgao.png') }}"/><br/>DOCUMENTO DO ORGÃO</div></a> 
		
		<div class="clear"></div>
        <br><br>   
	</div>
@endsection