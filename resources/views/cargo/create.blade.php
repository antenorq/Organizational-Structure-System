@extends('layouts.app')

@section('content')		

	{{ Form::open(['route'=>'cargo.store']) }}
		@include('cargo.form')

		<div align="center">                    
			<a href="{{ URL::route('cargo.index') }}" class="btn btn-primary">VOLTAR</a>
			{{ Form::submit('CADASTRAR', ['class' => 'btn btn-primary']) }}
		</div>
	{{ Form::close() }}

	<br/><br/>
	
@endsection