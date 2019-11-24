@extends('layouts.app');

@section('content')
	<div class="panel panel-default">
	<div class="title-show">{{ $usuario->name }}</div>
		<table class="table table-striped">
			<tbody>
				<tr>
					<td>
						<b>E-mail:</b> {{ $usuario->email }}
					</td>
				</tr>	
				<tr>
					<td>
						<b>Órgão:</b> {{ $usuario->orgao }}
					</td>
				</tr>	
				<tr>
					<td>
						<b>Perfil:</b> {{ $usuario->perfil }}
					</td>
				</tr>
				<tr>
					<td>
						<b>Criado em:</b> {{ $usuario->data_criacao }}
					</td>
				</tr>	
			</tbody>
		</table>
	</div>
	<a href="{{ URL::route('usuario.index') }}" class="btn btn-primary">VOLTAR</a>
	</div>
@endsection