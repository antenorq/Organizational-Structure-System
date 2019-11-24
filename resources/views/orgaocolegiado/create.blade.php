@extends('layouts.app')

@section('content')

    {{ Form::open(['route' => 'orgaocolegiado.store']) }}

        @include('orgaocolegiado.form')

        <div align="center">
		    	<a href="{{ URL::route('orgaocolegiado.index') }}" class="btn btn-primary">VOLTAR</a>
		    {{ Form::submit('CADASTRAR', ['class' => 'btn btn-primary']) }}
		</div>

		<br>

    {!! Form::close() !!}

@endsection
