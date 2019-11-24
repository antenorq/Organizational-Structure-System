<fieldset>
	<legend>ATO NORMATIVO</legend>

	<div class="form-group">
		<div class="col-md-3 {{ $errors->has('id_ato_normativo') ? 'has-error' :'' }}">
			{{ Form::label('ato_normativo', 'Número do Ato') }}
			@if($cargoOrgao->ato)
			{{ Form::text('ato_normativo.'.$key, $cargoOrgao->ato, ['class'=>'form-control ato_normativo','data-key'=>$key, 'name'=>'ato_normativo[]']) }}
			@else
			{{ Form::text('ato_normativo.'.$key, null, ['class'=>'form-control ato_normativo','data-key'=>$key, 'name'=>'ato_normativo[]']) }}			
			@endif
			
			@if($cargoOrgao->ato)
			{{ Form::hidden('id_ato_normativo.'.$key, $cargoOrgao->id_ato_normativo , ['class'=>'form-control', 'data-key'=>$key, 'name'=>'id_ato_normativo[]','id'=>'id_ato_normativo_'.$key]) }}
			@else
			{{ Form::hidden('id_ato_normativo.'.$key, null , ['class'=>'form-control', 'data-key'=>$key, 'name'=>'id_ato_normativo[]','id'=>'id_ato_normativo_'.$key]) }}
			@endif


			</div>
	</div>
</fieldset>