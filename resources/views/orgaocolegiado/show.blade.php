@extends('layouts.app')

@section('js')
	<script>
		$(document).ready(function() {
			//ALTERA A SITUAÇÃO DO ÓRGÃO CASO A SITUAÇÃO ATUAL FOR PENDENTE
			$("#btn-alterar-situacao").click(function() {
				var id_unidade = "<? echo $orgao_colegiado->id; ?>";
				var id_situacao = $("#situacao").val();
				
				$.ajax({
					url: "/situacao/alterar-situacao",
					type: "POST",
					dataType: "json",
					data: {estrutura: id_unidade, situacao: id_situacao, tipo: "estrutura"},
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
	<div class="title-show">ÓRGÃO COLEGIADO: {{$orgao_colegiado->descricao}}</div>
		<table class="table table-striped">
		    <tbody>
		      <tr>
		        <td><b>Sigla</b>: {{$orgao_colegiado->sigla}}<br/></td>
		      </tr>
		      <tr>
		        <td><b>Função</b>: {{$orgao_colegiado->funcao_descricao}}<br/></td>
		      </tr>
		      <tr>
	        	<td><b>Tipo</b>: {{$orgao_colegiado->tipo_hierarquia_descricao}}<br/></td>
	      	  </tr>
	      	  <tr>
	      	  	<td><b>Situação</b>: {{$orgao_colegiado->situacao_descricao}}<br/></td>
	      	  </tr>
	      	  @if($orgao_colegiado->data_fim)
		      	  <tr>
		      	  	<td><b>Data fim:</b> {{$orgao_colegiado->data_fim}}</td>
		      	  </tr>
		      @endif
	      	  <tr>
	      	  	<td><b>Competência</b>: <? echo html_entity_decode($orgao_colegiado->competencia);  ?></td>
	      	  </tr>
	      	  <tr>
	      	  	<td><b>Finalidade</b>: <? echo html_entity_decode($orgao_colegiado->finalidade);  ?></td>
	      	  </tr>
	      	  @if($orgao_relacionado)
				  <tr>
				  	<td><b>Órgão relacionado</b>: {{$orgao_relacionado->sigla}} - {{$orgao_relacionado->descricao}}</td>
				  </tr>
			  @endif
			  <tr>
				<td><b>Ato normativo</b>: {{$orgao_colegiado->tipo_ato_descricao}} - {{$orgao_colegiado->numero}} - {{$orgao_colegiado->data}}<br/></td>
			  </tr>
			  <tr>
				<td><b>Ato normativo observação</b>: {{$orgao_colegiado->observacao}}<br/></td>
			  </tr>
	    	</tbody>
	  	</table>
	  	@if(Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
	  		@if($orgao_colegiado->id_sit_estr_organizacional == 3)
	  			<div class="content-change-situation">
	  				<hr/>
	  				<p><b>Alterar Situação</b></p>
					{{ Form::select('id_sit_estr_organizacional', $situacoes, null, ['class' =>'form-control', 'id'=>'situacao', 'style'=>'width:200px']) }}<br/>
	  				{{ Form::button('ALTERAR', ['class' => 'btn btn-primary', 'id'=>'btn-alterar-situacao']) }}
	  			</div> 
	  		@endif	
	  	@endif
	  	
	</div>
@endsection