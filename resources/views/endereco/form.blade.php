<fieldset>
	<legend>ENDEREÇO</legend>

	<div class="form-group">
		<div class="col-md-2 {{ $errors->has('endereco.cep') ? 'has-error' :'' }}">
			{{ Form::label('cep', 'Cep') }}
			{{ Form::text('endereco[cep]', isset($endereco) ? $endereco->cep : '', ['class'=>'form-control','id'=>'cep']) }}
		</div>

		<div class="col-md-4 {{ $errors->has('endereco.logradouro') ? 'has-error' :'' }}">
			{{ Form::label('logradouro', 'Logradouro') }}
			{{ Form::text('endereco[logradouro]', isset($endereco) ? $endereco->logradouro : '', ['class'=>'form-control','id'=>'logradouro']) }}
		</div>

		<div class="col-md-2 {{ $errors->has('endereco.bairro') ? 'has-error' :'' }}">
			{{ Form::label('bairro', 'Bairro') }}
			{{ Form::text('endereco[bairro]', isset($endereco) ? $endereco->bairro : '', ['class'=>'form-control','id'=>'bairro']) }}
		</div>

		<div class="col-md-1 {{ $errors->has('endereco.numero') ? 'has-error' :'' }}">
			{{ Form::label('numero', 'Número') }}
			{{ Form::text('endereco[numero]', isset($endereco) ? $endereco->numero : '', ['class'=>'form-control','id'=>'numero']) }}
		</div>

		<div class="col-md-3 {{ $errors->has('endereco.complemento') ? 'has-error' :'' }}">
			{{ Form::label('complemento', 'Complemento') }}
			{{ Form::text('endereco[complemento]', isset($endereco) ? $endereco->complemento : '', ['class'=>'form-control','id'=>'complemento']) }}
		</div>

	</div>
</fieldset>

