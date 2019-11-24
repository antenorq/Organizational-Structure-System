@extends('layouts.app')

@section('content')
	{{ Form::model($atoNormativo, ['route'=>['atonormativo.update', $atoNormativo->id], 'method'=>'put', 'files'=>true]) }}

		@include('ato_normativo.form')

		<div align="center">                    
			<a href="{{ URL::route('atonormativo.index') }}" class="btn btn-primary">VOLTAR</a>
			{{ Form::submit('ATUALIZAR', ['class' => 'btn btn-primary']) }}
		</div>

	{{ Form::close() }}
@endsection
<br/>