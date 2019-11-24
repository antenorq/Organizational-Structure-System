@extends('layouts.app')

@section('content')

	{{ Form::open(['route' => 'organograma.show','method' => 'post']) }}
		<fieldset>
		    <legend>ORGANOGRAMA</legend>		
				<div class="form-group">
			        <div class="col-md-6">
			            {{ Form::label('id_orgao', 'Órgão:')}}
			            {{ Form::select('id_orgao', $orgaos, null, ['placeholder' => 'Selecione o órgão', 'class' =>'form-control orgao_organograma']) }}                    
			        </div>

			        <div class="col-md-2">
			        	{{ Form::label(' ', ' ',['style' => 'margin-top:16px;'])}}
			            {{ Form::submit('EXIBIR', ['class' => 'form-control btn btn-primary']) }}                    
			        </div>
			    </div>
		</fieldset>
	{!! Form::close() !!}

@endsection