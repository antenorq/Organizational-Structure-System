@extends('layouts.app')

@section('content')
	{{ Form::model($cargo, ['route'=>['cargo.update', $cargo->id], 'method'=>'put', 'files'=>true]) }}
		@include('cargo.form')
		
		@if(!empty($ato))
			<script>
				var ato = "<?php echo $ato; ?>";
				var ato_normativo = document.getElementById('ato_normativo');
				ato_normativo.value = ato;
			</script>
		@endif

		<div align="center">                    
			<a href="{{ URL::route('cargo.index') }}" class="btn btn-primary">CANCELAR</a>
			{{ Form::submit('ATUALIZAR', ['class' => 'btn btn-primary']) }}
		</div>
		<br/><br/>
	{{ Form::close() }}
@endsection