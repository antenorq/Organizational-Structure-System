@extends('layouts.app');

@section('content')
 {{ Form::model($usuario,['route' => ['usuario.update', $usuario->id], 'method' => 'PUT']) }}
 	
 	<fieldset>
	    <legend>DADOS DO USUÁRIO</legend>
	    
	    <div class="row">
	        <div class="form-group">
	            <div class="col-md-4">
	                {{ Form::label('name', 'Nome') }}
	                {{ Form::text('name', null, ['class' => 'form-control']) }}        
	            </div>

	              <div class="col-md-4">
	                {{ Form::label('id_orgao', 'Órgão') }}
	                {{ Form::select('id_orgao', $orgaos, null, ['class' => 'form-control', 'placeholder' => 'Selecione o órgão']) }}        
	            </div> 

	            <div class="col-md-4">
	                {{ Form::label('id_perfil', 'Perfil') }}
	                {{ Form::select('id_perfil', $perfils, null, ['class' => 'form-control', 'placeholder' => 'Selecione o perfil']) }}        
	            </div>
	        </div> 
	    </div>

	    <div class="row">
	        <div class="form-group">
	            <div class="col-md-4">
	                {{ Form::label('e-mail') }}
	                {{ Form::email('email', null, ['class' => 'form-control']) }}        
	            </div>
	        </div>    
   	 	</div>
    </fieldset>
	
	<div class="content-buttons">
		<div align="center">
		    <a href="{{ URL::route('usuario.index') }}" class="btn btn-primary">VOLTAR</a>
		    {{ Form::submit('SALVAR', ['class' => 'btn btn-primary']) }}
		</div>
	</div>

 {{ Form::close() }}
@endsection