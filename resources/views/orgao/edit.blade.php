@extends('layouts.app')

@section('content')

    {{ Form::model($orgao, ['route' => ['orgao.update', $orgao->id],'method' => 'PUT']) }}

        @include('orgao.form')

		@if(!empty($ato))
			<script>
				var ato = "<?php echo $ato; ?>";
				var ato_normativo = document.getElementById('ato_normativo');
				ato_normativo.value = ato;
			</script>
		@endif

	<div align="center">
		<a href="{{ URL::route('orgao.index') }}" class="btn btn-primary">CANCELAR</a>
    	{{ Form::submit('ATUALIZAR', ['class' => 'btn btn-primary']) }}
		{{ Form::hidden('id', $orgao->id, array('id' => '$orgao_id')) }}
	</div>
	<br/>
	<br/>

    {!! Form::close() !!}

@endsection
