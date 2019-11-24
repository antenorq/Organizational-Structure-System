<fieldset>
	<legend>ATO NORMATIVO</legend>

	<div class="form-group">
		<div class="col-md-3 {{ $errors->has('id_ato_normativo') ? 'has-error' :'' }}">
			{{ Form::label('ato_normativo', 'Número do Ato') }}
			{{ Form::text('ato_normativo', null, ['class'=>'form-control ato_normativo','data-key'=>0]) }}

			{{ Form::hidden('id_ato_normativo', null, ['class'=>'form-control', 'id'=>'id_ato_normativo_0']) }}
		</div>

		<div class="col-md-2 {{ $errors->has('id_tipo_acao_ato_normativo') ? 'has-error' :'' }}">
			{{ Form::label('id_tipo_acao_ato_normativo', 'Ação') }}
			{{ Form::select('id_tipo_acao_ato_normativo', $tiposAcaoAtoNormativo, null, ['class'=>'form-control ', 'placeholder'=>'Selecione']) }}
		</div>

		<div class="col-md-7">
			{{ Form::label('obs_ato_normativo', 'Observação') }}
			{{ Form::text('obs_ato_normativo', null, ['class'=>'form-control']) }}
		</div>
	</div>	
</fieldset>