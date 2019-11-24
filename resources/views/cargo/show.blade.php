@extends('layouts.app')

@section('js')
	<script>
		$(document).ready(function() {
			//ALTERA A SITUAÇÃO DO ÓRGÃO CASO A SITUAÇÃO ATUAL FOR PENDENTE
			$("#btn-alterar-situacao").click(function() {
				var id_cargo = "<? echo $cargo->id; ?>";
				var id_situacao = $("#situacao").val();
				
				$.ajax({
					url: "/situacao/alterar-situacao",
					type: "POST",
					dataType: "json",
					data: {cargo: id_cargo, situacao: id_situacao, tipo: "cargo"},
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
		<div class="title-show">CARGO: {{$cargo->cargo_descricao}}</div>
			<table class="table table-striped">
			    <tbody>
			      <tr>
			        <td><b>Tipo</b>: {{$cargo->tipo_descricao}}<br/></td>
			      </tr>
			      <tr>
			        <td><b>Quantidade</b>: {{$cargo->qtde}}<br/></td>
			      </tr>
			      <tr>
		        	<td><b>Grau</b>: {{$cargo->grau}}<br/></td>
		      	  </tr>
		      	  <!--
		      	  <tr>
		      	  	<td><b>Ato normativo</b>: {{--$cargo->tipoato_descricao--}} - {{--$cargo->numero}} - {{$cargo->data--}}<br/></td>
		      	  </tr>
		      	  <tr>
					<td><b>Ação</b>: {{--$cargo->acaoato_descricao--}}<br/></td>
				  </tr>
				  -->
				  <tr>
					<td><b>Observação</b>: <?php echo !empty($cargo->obs_ato_normativo) ? $cargo->obs_ato_normativo : '-' ?></td>
				  </tr>
				  <tr>
		      	  	<td><b>Atribuições</b>: <br/> <? echo html_entity_decode($cargo->atribuicao);  ?> <br/>
		      	   </tr>
		    	</tbody>
		  	</table>
		  	@if(Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
	  		@if($cargo->id_situacao == 3)
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