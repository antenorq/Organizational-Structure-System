@extends('layouts.app')

@section('js')
	<script>
		$(document).ready(function() {
			//ALTERA A SITUAÇÃO DO ÓRGÃO CASO A SITUAÇÃO ATUAL FOR PENDENTE
			$("#btn-alterar-situacao").click(function() {
				var id_orgao = "<? echo $orgao->id; ?>";
				var id_situacao = $("#situacao").val();
				
				$.ajax({
					url: "/situacao/alterar-situacao",
					type: "POST",
					dataType: "json",
					data: {estrutura: id_orgao, situacao: id_situacao, tipo: "estrutura"},
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
	<div class="title-show">ÓRGÃO: {{$orgao->descricao}}</div>
		<table class="table table-striped">
		    <tbody>
		      <tr>
		        <td><b>Sigla</b>: {{$orgao->sigla}}<br/></td>
		      </tr>
		      <tr>
		        <td><b>Função</b>: {{$orgao->funcao_descricao}}<br/></td>
		      </tr>
		      <tr>
	        	<td><b>Tipo de Administração</b>: {{$orgao->tipo_hierarquia_descricao}}<br/></td>
	      	  </tr>
	      	  <tr>
	      	  	<td><b>Situação</b>: {{$orgao->situacao_descricao}}<br/></td>
	      	  </tr>
	      	  @if($orgao->data_fim)
		      	  <tr>
		      	  	<td><b>Data fim:</b> {{$orgao->data_fim}}</td>
		      	  </tr>
		      @endif
	      	  <tr>
				<td><b>CPNJ</b>: {{$orgao->cnpj}}<br/></td>
			  </tr>
	      	  <tr>
	      	  	<td><b>Competência</b>: <? echo html_entity_decode($orgao->competencia);  ?></td>
	      	  </tr>
	      	  <tr>
	      	  	<td><b>Finalidade</b>: <? echo html_entity_decode($orgao->finalidade);  ?></td>
	      	  </tr>
	      	  <tr>
				<td><b>Horário de funcionamento</b>: {{$orgao->horario_funcionamento}}<br/></td>
			  </tr>
			  <tr>
				<td><b>Ato normativo</b>: {{$orgao->tipo_ato_descricao}} - {{$orgao->numero_ato}} - {{$orgao->data}}<br/></td>
			  </tr>
			  <tr>
				<td><b>Ato normativo observação</b>: {{$orgao->obs_ato_normativo}}<br/></td>
			  </tr>
			  @if($vinculacao)
				  <tr>
				  	<td><b>Vinculação</b>: {{$vinculacao->sigla}} - {{$vinculacao->descricao}}</td>
				  </tr>
			  @endif
			  @if($unidade_representacao)
				  <tr>
				  	<td><b>Representacão PGMS</b>: {{$unidade_representacao->sigla}} - {{$unidade_representacao->descricao}}</td>
				  </tr>
			  @endif
			  <tr>
				<td>
					<p><b>Contatos:</b></p>
					<b>Telefone</b>: {{$orgao->telefone}}<br/>
			 		<b>E-mail</b>: {{$orgao->email}}<br/> 
					<b>Site</b>: {{$orgao->email}}<br/>
				</td>
			  </tr>
			  <tr>
				<td>
					<p><b>Endereço:</b></p>
					<b>Bairro</b>: {{$orgao->bairro}}<br/>
			 		<b>Logradouro</b>: {{$orgao->logradouro}}<br/>
			 		<b>Complemento</b>: {{$orgao->complemento}}<br/> 
					<b>CEP</b>: {{$orgao->cep}}<br/>
					<b>Número</b>: {{$orgao->numero_endereco}}<br/>
				</td>
			  </tr>
	    	</tbody>
	  	</table>
	  	@if(Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
	  		@if($orgao->id_sit_estr_organizacional == 3)
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