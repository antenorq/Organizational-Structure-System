@extends('layouts.app')

@section('content')
<fieldset>
	{{ Form::open(['route' => 'cadastro-organizacional.show', 'method' => 'post','target'=>'_blank']) }}
	    <legend>CADASTRO ORGANIZACIONAL</legend>		
			<div class="form-group">
		        <div class="col-md-6">
		            {{ Form::label('id_orgao', 'Órgão:')}}
		            {{ Form::select('id_orgao', $orgaos, null, ['placeholder' => 'Selecione o órgão', 'class' =>'form-control cadastro-organizacional-orgao']) }}                    
		        </div>

		        <div class="col-md-2">
		            {{ Form::submit('GERAR', ['class' => 'form-control btn btn-primary', 'style' => 'margin-top: 25px']) }}                    
		        </div>
		    </div>
	{{ Form::close() }}
</fieldset>

@endsection