@extends('layouts.app')

@section('content')
	
	{{ Form::open(['route'=>'equipe.store', 'files'=>true]) }}
		@include('equipe.form')

	<div align="center">                    
		<a href="{{ URL::route('equipe.index') }}" class="btn btn-primary">VOLTAR</a>
		{{ Form::submit('CADASTRAR', ['class' => 'btn btn-primary']) }}
	</div>

	<br/><br/>

	{{ Form::close() }}

@endsection

