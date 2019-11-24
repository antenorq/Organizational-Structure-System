@section('js')
	<script>
	    $(document).ready(function()
	    {
	        $("#foto").change(function(){
	        	filePreview(this);
			});

			function filePreview(input) {
				if(input.files && input.files[0]) {
					var reader = new FileReader();
					reader.onload = function(e) {
						$(".img-gestor").attr("src", e.target.result).css("width", "128px");
					}
					reader.readAsDataURL(input.files[0]);
				}
			}
	    });
	</script>
@endsection

<fieldset>
	<legend>Gestor</legend>
	{{ Form::hidden('gestor[id]', $gestor->id) }}
	<div class="form-group">
		<div class="col-md-2 {{ $errors->has('gestor.foto') ? 'has-error' :'' }}">
			{{ Form::label('img', 'Foto do Gestor', ['class'=>'label-gestor']) }}
			<img width="120" src="{{ $gestor->foto == null ? asset('images/gestor.png') : $gestor->foto }}" class="img-gestor">

			<label class="btn btn-default btn-file">
				CARREGAR FOTO {{ Form::file('gestor[foto]', ['id'=>'foto'])}}
			</label>
		</div>

		<div class="col-md-5 {{ $errors->has('gestor.id_orgao') ? 'has-error' :'' }}">
			{{ Form::label('id_orgao', 'Órgão') }}
			{{ Form::select('gestor[id_orgao]', $estruturasOrgao, $gestor->id_orgao, ['class'=>'form-control', 'id'=>'gestor', 'placeholder'=>'Selecione o órgão']) }}
		</div>
	</div>

	<br/><br/><br/>

	<div class="form-group">
		<div class="col-md-5 {{ $errors->has('gestor.nome') ? 'has-error' :'' }}">
			{{ Form::label('nome', 'Nome') }}
			{{ Form::text('gestor[nome]', $gestor->nome, ['class'=>'form-control']) }}
		</div>

		<div class="col-md-5 {{ $errors->has('gestor.id_cargo') ? 'has-error' :'' }}">
			{{ Form::label('id_cargo_gestor', 'Cargo do Gestor') }}
			{{ Form::select('gestor[id_cargo]', $cargos, $gestor->id_cargo, ['class'=>'form-control', 'placeholder'=>'Selecione o cargo']) }}
		</div>
	</div>

	<br/><br/><br/><br/><br/>

	<div class="form-group">
		<div class="col-md-12 {{ $errors->has('gestor.curriculo') ? 'has-error' :'' }}" style='margin-top: 20px'>
			{{ Form::label('curriculo', 'Currículo breve') }}
			{{ Form::textarea('gestor[curriculo]', $gestor->curriculo, ['class'=>'form-control', 'size'=>'30x5']) }}
		</div>
	</div>

</fieldset>

<fieldset>
	<legend>Equipe</legend>
	<img src="{{ asset('/images/add.png') }}" data-container="container-membro" class="add-icon" style='margin-left: 8px'>
	@foreach($equipe as $key => $membro)
		@include('equipe._membros')
	@endforeach
</fieldset>

<br/>

<br/><br/>
