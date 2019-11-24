<?php
	//SE FOI CLICADO NO BOTÃO ATRIBUIÇÕES
	$bt_atribuicoes = null;
	if(isset($_GET['atribuicoes']))
	{
		$bt_atribuicoes = $_GET['atribuicoes'];
	}
	/*
	if(old('atribuicao.1'))
	{
		$valor = old('atribuicao.1');
		$campo = "<input type=text name=atribuicao[] id=atribuicao class='form-control atribuicao' style='width:90%' autocomplete=off value='".$valor."'>";
		echo $campo;
	}
	*/
?>

<fieldset>
	<legend>
		<?php echo ($bt_atribuicoes == null  ? "DADOS DO CARGO COMISSIONADO E FUNÇÃO DE CONFIANÇA" : " CADASTRO DAS ATRIBUIÇÕES DO CARGO" ) ?>
	</legend>

	<div class="form-group">
		<div class="col-md-3 {{ $errors->has('id_tipo_cargo') ? 'has-error' :'' }}">
			{{ Form::label('id_tipo_cargo', 'Tipo') }}
			{{ Form::select('id_tipo_cargo', $tiposCargo, null, ['class'=>'form-control','disabled'=> is_null($bt_atribuicoes) ? false : 'disabled','placeholder'=>'Selecione']) }}		
		</div>

		<div class="col-md-5 {{ $errors->has('descricao') ? 'has-error' :'' }}">
			{{ Form::label('descricao', 'Denominação') }}
			{{ Form::text('descricao', null, ['class'=>'form-control',  'disabled'=> is_null($bt_atribuicoes) ? false : 'disabled'  ,'autocomplete'=>'off']) }}
		</div>

		<div class="col-md-2 {{ $errors->has('qtde') ? 'has-error' :'' }}">
			{{ Form::label('qtde', 'Quantidade Total') }}
			{{ Form::text('qtde', null, ['class'=>'form-control','disabled'=> is_null($bt_atribuicoes) ? false : 'disabled']) }}
		</div>

		<div class="col-md-2 {{ $errors->has('grau') ? 'has-error' :'' }}">
			{{ Form::label('grau', 'Grau') }}
			{{ Form::text('grau', null, ['class'=>'form-control','disabled'=> is_null($bt_atribuicoes) ? false : 'disabled']) }}
		</div>
	</div>

	<br/><br/><br/>

	<br>
	<fieldset>
		<legend>QUANTIDADE DE CARGO POR ÓRGÃO</legend>
		@if($bt_atribuicoes == null)
			Adicionar quantidade desse cargo no Órgão <img src="{{ asset('/images/add.png') }}" class="add-icon" data-container="cargo-container" style='margin-left: 8px'>
		<br/><br/>
		@endif

		@foreach($cargosOrgao as $key => $cargoOrgao)
			@include('cargo._qtd_cargo',['bt_atribuicoes'=>$bt_atribuicoes])
		@endforeach
	</fieldset>	

	<!--
    <div class="form-group">
    	<div class="col-md-12 {{-- $errors->has('atribuicao') ? 'has-error' :'' --}}">
    		{{-- Form::label('atribuicao', 'Atribuições') --}}
    		{{-- Form::textArea('atribuicao', null, ['class' => 'form-control']) --}}
    	</div>
    </div>
	   
    <div style="clear:both;"></div>
    <br/>

    <div class="form-group">
    	<div class="col-md-12">
			{{-- Form::label('obs_ato_normativo', 'Observação') --}}
			{{-- Form::text('obs_ato_normativo', null, ['class'=>'form-control']) --}}
		</div>
    </div>
	-->

</fieldset>

<br/>

<!--  ATO NORMATIVO (Se NÃO veio do botão atribuições do datatable ) -->
@if($bt_atribuicoes == null)
	@include('ato_normativo.form-escolha')
@else
	{{ Form::hidden('atribuicoes', 1) }}
@endif

<br/>

