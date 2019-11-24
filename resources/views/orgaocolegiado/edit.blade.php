@extends('layouts.app')

@section('content')

    {{ Form::model($orgaocolegiado, ['route' => ['orgaocolegiado.update', $orgaocolegiado->id],'method' => 'PUT']) }}

        @include('orgaocolegiado.form')

        @if(!empty($ato))
			<script>
				var ato = "<?php echo $ato; ?>";
				var ato_normativo = document.getElementById('ato_normativo');
				ato_normativo.value = ato;
			</script>
		@endif

		<div align="center">
		    <a href="{{ URL::route('orgaocolegiado.index') }}" class="btn btn-primary">CANCELAR</a>
		    {{ Form::submit('ATUALIZAR', ['class' => 'btn btn-primary']) }}
		</div>

<br>

    {!! Form::close() !!}

@endsection
