@extends('layouts.app')

@section('js')
	<script>
		//Ao carregar página busca todas as unidades do órgão
		$(document).ready(function() {
			var orgao = "<? echo $gestor->id_orgao; ?>";

			$.ajax({
				url: "/unidade/busca-unidades",
				type: "POST",
				dataType: "json",
				data: {id_orgao: orgao, tipo: 2},
				success: function(data){
					$.each(data, function(key, value) {
						$(".unidade").append("<option value="+key+">"+value+"</option>");
					});
				},
			});

			$.ajax({
				url: "/cargo/busca-cargos",
				type: "POST",
				dataType: "json",
				success: function(data) {
					$.each(data, function(key, value) {
						$(".cargo").append("<option value="+key+">"+value+"</option>");
					});
				},
				error: function(e) {
					alert("error");
				}
			});

		});
	</script>
@endsection

@section('content')

	{{ Form::model($gestor, ['route'=>['equipe.update', $gestor->id_orgao], 'method'=>'put', 'files'=>true]) }}

		@include('equipe.form')

		<div align="center">
			<a href="{{ URL::route('equipe.index') }}" class="btn btn-primary">CANCELAR</a>
			{{ Form::submit('ATUALIZAR', ['class' => 'btn btn-primary']) }}
		</div>

		<br/><br/>

	{{ Form::close() }}

@endsection
