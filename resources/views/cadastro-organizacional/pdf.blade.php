<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Cadastro Organizacional</title>
	<link rel="stylesheet" href="{{ asset('css/cadastro-organizacional.css') }}">
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">



</head>

<?
	//$orgao = $data['orgao'];
	//$cargosUnidade = $data['cargosUnidade'];
	//$funcoesUnidade = $data['funcoesUnidade'];
	//$atosNormativos = $data['atosNormativos'];
?>

<body>
	<center>
	<div id="geral">
		<div id="capa">
			<img src="{{ asset('images/back_cadastro_organizacional.jpg') }}">

			<div id="nome_descricao_orgao_capa" >		
				<h1 style="font-size: 25px;">{{ $orgao->sigla }}</h1>
				<div style="width: 430px; font-size: 16px;">{{ $orgao->descricao }}</div>	
			</div>
		</div>

		<div class="page_break"></div>		

		<div class="organograma" id="landscape">
			{{-- @ include('organograma.organograma',['retira_botoes' => 1]) --}}
		</div>

		<div class="page_break"></div>

		<br>
		<br>

		<div class="orgao-atributos">
			<div class="title"><b>INFORMAÇÕES BÁSICAS DO ÓRGÃO</b></div>
			<br>
			<table>
				<tr>
					<td class="width-orgao-atributos">Órgão/Sigla:</td>
					<td>{{ $orgao->descricao }} - {{ $orgao->sigla }}</td>
				</tr>
				<tr>
					<td class="width-orgao-atributos">Natureza Jurídica:</td>
					<td>{{ $orgao->tipoHierarquia->descricao }}</td>
				</tr>
				<tr>
					<td class="width-orgao-atributos">Subordinação:</td>
					<td>
						@if($orgao->id_orgao_vinculacao)   
							{{ $orgao->getSigla($orgao->id_orgao_vinculacao) }}
						@else
							O Prefeito 
						@endif 
					</td>
				</tr>
				<tr>
					<td class="width-orgao-atributos">Finalidade:</td>
					<td style="text-align: justify"><? echo html_entity_decode($orgao->finalidade); ?></td>
				</tr>
				<tr>
					<td class="width-orgao-atributos">Criação:</td>
					<td>{{ $orgao->atoNormativo->tipo_ato_normativo }} n°{{ $orgao->atoNormativo->numero }} de <? echo $orgao->atoNormativo->data['dia']; ?> de <? echo $orgao->atoNormativo->data['mes']; ?> de <? echo $orgao->atoNormativo->data['ano']; ?></td>
				</tr>
			</table>
		</div>

		<br>
		<br>

		<div class="estrutura-basica">
			<div class="title"><b>ESTRUTURA BÁSICA</b></div>			

			@if($atosNormativos->first()->fl_tem_doc)
				<br>
				<a href="{{asset($atosNormativos->first()->caminho_documento)}}" target="_blank" class="btn btn btn-primary"><i class="glyphicon glyphicon-save-file"></i> Pdf do Ato Normativo de criação</a>
			@else
				<br>
				<br>
				<p><b>{{$atosNormativos->first()->tipo_ato}} Nº {{$atosNormativos->first()->numero}} DE {{ \Carbon\Carbon::parse($atosNormativos->first()->data_publicacao)->format('d/m/Y')}}</b></p>
					
				{{--dd($atosNormativos->last())--}}

				<div class="caput justify">
					{{ $atosNormativos->first()->caput }}
				</div>

				<br>

				<div class="clear"></div>

				<br>				

				<div class="justify">{{ $atosNormativos->first()->introducao }}</div>

				<br>
				
				<div class="justify">{!! $atosNormativos->first()->conteudo !!}</div>
				<br>
				<br>
				<!-- 
				<div class="title"><b>GABINETE DO PREFEITO</b></div>
				<BR>

				
				<p><b><center>GABINETE DO PREFEITO MUNICIPAL DO SALVADOR,</b> EM 16 DE FEVEREIRO DE 2017.</center></p>

				<div class="nome-prefeito">
					<p><b>ANTÔNIO CARLOS PEIXOTO DE MAGALHÃES NETO</b><br/>Prefeito</p>	
				</div>
			
				<table style="text-align: center; width: 100%;" cellpadding="0px" border="0">
					<tr>
						<td class="nome-gestor" ><b>JOÃO INÁCIO RIBEIRO ROMA NETO</b><br>Chefe de Gabinete do Prefeito</td>
						<td class="nome-gestor" ><b>LUIZ ANTÔNIO VASCONCELLOS CARREIRA</b><br>Chefe da Casa Civil</td>
					</tr>					
					<tr>
						<td class="nome-gestor" ><b>THIAGO MARTINS DANTAS</b><br>Secretário Municipal da Gestão</td>
						<td class="nome-gestor" ><b>PAULO EZEQUIEL DE ALENCAR SILVA</b><br>Secretário Municipal de Comunicação</td>
					</tr>					
					<tr>
						<td class="nome-gestor" ><b>GERALDO ALVES FERREIRA</b><br>Secretário Municipal do Trabalho, Esportes e Lazer</td>
						<td class="nome-gestor" ><b>TAISSA TEIXEIRA SANTOS DE VASCONCELLOS</b><br>Secretária Municipal de Políticas para Mulheres, Infância e Juventude</td>
					</tr>					
				</table>
				-->

			@endif
		</div>

		<br>

		<div class="page_break"></div>

		<div id="area-quadro-cargos-funcoes">
			@if(count($cargosUnidade) > 0)
				<div class="title"><b>QUADRO DE CARGOS EM COMISSÃO - {{ $orgao->sigla }}</b></div>
				<br>			
				
				<table class="tabela-cargos-funcoes">
					<tr>
						<th>GRAU</th>
						<th>QUANTIDADE</th>
						<th>DENOMINAÇÃO</th>
						<th>VINCULAÇÃO</th>
					</tr>
					@foreach($cargosUnidade as $key => $value)
						<tr>
							<td>{{ $value->grau }}</td>
							<td>{{ $value->qtde }}</td>
							<td>{{ $value->cargo }}</td>
							<td>{{ $value->unidade }}</td>
						</tr>
					@endforeach
				</table>
			@endif

			<br>
			<br>

			<div class="page_break"></div>

			@if(count($funcoesUnidade) > 0)
				<div class="title"><b>QUADRO DE FUNÇÕES DE CONFIANÇA - {{ $orgao->sigla }}</b></div>			
				<br>			
							
				<table class="tabela-cargos-funcoes">
					<tr>
						<th>GRAU</th>
						<th>QUANTIDADE</th>
						<th>DENOMINAÇÃO</th>
						<th>VINCULAÇÃO</th>
					</tr>
					@foreach($funcoesUnidade as $key => $value)
					<tr>
						<td>{{ $value->grau }}</td>
						<td>{{ $value->qtde }}</td>
						<td>{{ $value->cargo }}</td>
						<td>{{ $value->unidade }}</td>
					</tr>
					@endforeach
				</table>
			@endif
		</div>

		<div class="page_break"></div>
		
		<br>
		<br>
		<div id="content-legislacao">
			<p class="title"><b>LEGISLAÇÃO</b></p>
			<br>

			<?php $legislacoes_adicionadas = array(); ?>

			@foreach($atosNormativos as $key => $value)
				<?php
					$data_publicacao = new DateTime($value->data_publicacao);
					$tipo_numero_ano = $value->tipo_ato.'-'.$value->numero.'-'.$value->ano;

					//SE NÃO EXISTIR NO ARRAY ligislacoes_adicionadas então escreve a legislação
					if(!in_array($tipo_numero_ano, $legislacoes_adicionadas))
					{ 
				?>
						<ul>
							<li><b>{{ $value->tipo_ato }} nº {{ $value->numero }}/{{$value->ano}}</b> - {{ $value->caput }} - Data Publicação: {{ $data_publicacao->format('d/m/Y') }} .</li>
						</ul>
				<?php 
					}
					$legislacoes_adicionadas[$key] = $value->tipo_ato.'-'.$value->numero.'-'.$value->ano;
				?>					
			@endforeach
		</div>
		<br>
		<br>

	</div>
	<!-- FIM GERAL -->

	</center>
</body>
</html>