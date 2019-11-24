@extends('layouts.app')

@section('js')
	<script>
		$(document).ready(function() {
			//ALTERA A SITUAÇÃO DO ÓRGÃO CASO A SITUAÇÃO ATUAL FOR PENDENTE
			$("#btn-alterar-situacao").click(function() {
				var id_unidade = "<? echo $unidade->id; ?>";
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
	<div class="title-show">UNIDADE: {{$unidade->descricao}}</div>
		<table class="table table-striped">
		    <tbody>
		      <tr>
		        <td><b>Sigla</b>: {{$unidade->sigla}}</td>
		      </tr>
		      <tr>
		        <td><b>Tipo de unidade</b>: {{$unidade->tipo_hierarquia_descricao}}</td>
		      </tr>
	      	  <tr>
	      	  	<td><b>Situação</b>: {{$unidade->situacao_descricao}}</td>
	      	  </tr>
	      	  @if($unidade->data_fim)
		      	  <tr>
		      	  	<td><b>Data fim:</b> {{$unidade->data_fim}}</td>
		      	  </tr>
		      @endif
	      	  <tr>
	      	  	<td><b>Competência</b>: <? echo html_entity_decode($unidade->competencia);  ?></td>
	      	  </tr>
	      	  <tr>
	      	  	<td><b>Finalidade</b>: <? echo html_entity_decode($unidade->finalidade);  ?></td>
	      	  </tr>
			  <tr>
			  @if($unidade_subordinada)
				  <tr>
				  	<td><b>Unidade subordinada:</b> {{$unidade_subordinada->sigla}} - {{$unidade_subordinada->descricao}}</td>
				  </tr>
			  @endif
			  </tr>
				<td><b>Ato normativo observação:</b> {{$unidade->observacao}}</td>
			  </tr>
			 <tr>
			 	<td><b>Ato normativo:</b> {{$unidade->tipo_ato_descricao}} - {{$unidade->numero}} - {{$unidade->data}}</td>
			 </tr>
			 <tr>
	      	  	<td>
	      	  		<div class="content-equipe">
		      	  		<b>Cargos:</b>
		      	  		@foreach($cargos as $value)
		      	  		 	<span>
		      	  		 		{{$value->descricao}}<br/>
		      	  		 		Quantidade: {{$value->qtde}}<br/>
		      	  		 		Grau: {{$value->grau}}<br/>
		      	  		 	</span>
		      	  		@endforeach
	      	  		</div>
	      	   </tr>
	    	</tbody>
	  	</table>
	  	@if(Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
	  		@if($unidade->id_sit_estr_organizacional == 3)
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