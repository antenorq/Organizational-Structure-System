@extends('layouts.app')
@section('content')
    {{ Form::open(['route' => 'atonormativo.store', 'files'=>true]) }}

        @include('ato_normativo.form')

        <div align="center">                    
			<a href="{{ URL::route('atonormativo.index') }}" class="btn btn-primary">VOLTAR</a>
			{{ Form::submit('CADASTRAR', ['class' => 'btn btn-primary']) }}
		</div>
		<br/>
		<br/>
    {!! Form::close() !!}

@endsection



