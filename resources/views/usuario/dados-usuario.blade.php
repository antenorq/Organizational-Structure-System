@extends('layouts.app')

@section('content')
	{{ Form::model($usuario, ['route' => ['usuario.atualizar-dados'], 'method' => 'POST']) }}
		<fieldset>
		    <legend>DADOS DO USUÁRIO</legend>
		    
			{{ Form::hidden('id', $usuario->id) }}

		    <div class="row">
		        <div class="form-group">
		            <div class="col-md-4">
		                {{ Form::label('name', 'Nome') }}
		                {{ Form::text('name', null, ['class' => 'form-control']) }}        
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
		    
		    <div class="row">
				<div class="form-group">
		            <div class="col-md-4">
		                {{ Form::label('password', 'Senha') }}
		                {{ Form::password('password', ['class' => 'form-control']) }}        
		            </div>

		            <div class="col-md-4">
		                {{ Form::label('password_confirm', 'Confirmar senha') }}
		                {{ Form::password('password_confirm', ['class' => 'form-control', 'autocomplete' => 'off']) }}
		            </div>
		        </div>
		    </div>    
	</fieldset>

	<div class="content-buttons">
		<div align="center">
		    <a href="{{ URL::route('home') }}" class="btn btn-primary">VOLTAR</a>
		    {{ Form::submit('SALVAR', ['class' => 'btn btn-primary']) }}
		</div>
	</div>

	{{ Form::close() }}
@endsection