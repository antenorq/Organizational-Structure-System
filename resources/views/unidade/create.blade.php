@extends('layouts.app')

@section('content')

    {{ Form::open(['route' => 'unidade.store']) }}

        @include('unidade.form')

        <div align="center">
		    	<a href="{{ URL::route('unidade.index') }}" class="btn btn-primary">VOLTAR</a>
		    {{ Form::submit('CADASTRAR', ['class' => 'btn btn-primary']) }}
		</div>
		<br>
		<br/>

    {!! Form::close() !!}

@endsection
