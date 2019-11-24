@extends('layouts.app')

@section('js')
	<script>
		$(document).ready(function() {
			//ALTERA A SITUAÇÃO DO ÓRGÃO CASO A SITUAÇÃO ATUAL FOR PENDENTE
			$("#btn-alterar-situacao").click(function() {
				var id_ato = "<? echo $ato->id; ?>";
				var id_situacao = $("#situacao").val();
				
				$.ajax({
					url: "/situacao/alterar-situacao",
					type: "POST",
					dataType: "json",
					data: {ato: id_ato, situacao: id_situacao, tipo: "ato"},
					success: function(data) {
						alert("Alterado situação com sucesso!");
						location.reload();
					},
					error: function(e) {
						alert("Não foi possível alterar a situação, por favor tente novamente!");
					}
				});
			});
			
		});
	</script>
@endsection

@section('content')
	
	<div class="panel panel-default">
	<div class="title-show">ATO NORMATIVO</div>
		<table class="table table-striped">
		    <tbody>
		      <tr>
		        <td><b>Número</b>: {{$ato->numero}}<br/></td>
		      </tr>
		      <tr>
		        <td><b>Tipo</b>: {{$ato->tipo}}<br/></td>
		      </tr>
		      <tr>
	        	<td><b>Data</b>: {{$ato->data}}<br/></td>
	      	  </tr>
	      	  <tr>
	        	<td><b>Data de publicação</b>: {{$ato->data_publicacao}}<br/></td>
	      	  </tr>
	      	  <tr>
	        	<td><b>Caput</b>: {{$ato->caput}}<br/></td>
	      	  </tr>
	      	  <tr>
	        	<td><b>Situação</b>: {{$ato->situacao}}<br/></td>
	      	  </tr>
	      	  <tr>
	      	  	<td><b>Observação</b>: {{$ato->observacao}}<br/></td>
	      	  </tr>

	      	  @if($ato->fl_tem_doc == 0)
		      	  <tr>
		        	<td><b>Introdução</b>: {{$ato->introducao}}<br/></td>
		      	  </tr>
		      	  <tr>
		        	<td><b>Conteúdo</b>: {!!$ato->conteudo!!}<br/></td>
		      	  </tr>
	      	  @endif

	  	</table>
	  	@if(Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
	  		@if($ato->id_situacao == 3)
	  			<div class="content-change-situation">
	  				<hr/>
	  				<p><b>Alterar Situação</b></p>
					{{ Form::select('id_situacao', $situacoes, null, ['class' =>'form-control', 'id'=>'situacao', 'style'=>'width:200px']) }}<br/>
	  				{{ Form::button('ALTERAR', ['class' => 'btn btn-primary', 'id'=>'btn-alterar-situacao']) }}
	  			</div> 
	  		@endif	
	  	@endif
	  	
	</div>
@endsection