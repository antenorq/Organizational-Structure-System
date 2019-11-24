@extends('layouts.app')

@section('content')

    {{ Form::open(['route' => 'orgao.store']) }}

        @include('orgao.form')

        <div align="center">
		    <a href="{{ URL::route('orgao.index') }}" class="btn btn-primary">VOLTAR</a>
		    {{ Form::submit('CADASTRAR', ['class' => 'btn btn-primary']) }}
		</div>
		<br/>
		<br/>

    {!! Form::close() !!}
@endsection


