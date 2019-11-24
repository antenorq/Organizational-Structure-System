@extends('layouts.app')

@section('content')

    {{ Form::model($unidade, ['route' => ['unidade.update', $unidade->id],'method' => 'PUT']) }}

        @include('unidade.form')

        @if(!empty($ato))
			<script>
				var ato = "<?php echo $ato; ?>";
				var ato_normativo = document.getElementById('ato_normativo');
				ato_normativo.value = ato;
			</script>
		@endif

		<div align="center">
		    <a href="{{ URL::route('unidade.index') }}" class="btn btn-primary">CANCELAR</a>
		    {{ Form::submit('ATUALIZAR', ['class' => 'btn btn-primary']) }}
		</div>
		<br/>
		<br/>

    {!! Form::close() !!}

@endsection
