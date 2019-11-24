@extends('layouts.app')

@section('content')
 {{ Form::open(['route' => 'usuario.store']) }}

 	@include('usuario.form')

	<div class="content-buttons">
		<div align="center">
		    <a href="{{ URL::route('usuario.index') }}" class="btn btn-primary">VOLTAR</a>
		    {{ Form::submit('CADASTRAR', ['class' => 'btn btn-primary']) }}
		</div>
	</div>
	
 {{ Form::close() }}
@endsection