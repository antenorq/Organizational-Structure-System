@extends('layouts.app')

@section('content')
<fieldset>
    <legend>HISTÓRICO DO ÓRGÃO COLEGIADO</legend>
	
    <div class="form-group">
        <div class="col-md-6">
            {{ Form::select('id_conselho', $orgaos_colegiados, null, ['placeholder' => 'Selecione um órgão colegiado', 'class' =>'form-control estrutura-historico']) }}                    
        </div>
    </div>

	<div class="clear"></div>

	<hr/>			

	<div class="content-historico">
		<table class="table table-striped">
			<thead>
				<th>DATA</th>
				<th>SIGLA</th>
				<th>DESCRIÇÃO</th>
				<th>OPERAÇÃO</th>
				<th>ATO NORMATIVO</th>
				<th>Mais detalhes</th>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</fieldset>

<br/>
<div align="center">
	<a href="{{ URL::route('historico.index') }}" class="btn btn-primary">VOLTAR</a>
</div>
<br/>

@endsection
