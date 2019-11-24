@extends('layouts.app')

@section('content')
<fieldset>
    <legend>HISTÓRICO DA UNIDADE</legend>
	
    <div class="form-group">
		<div class="col-md-6">
            {{ Form::select('id_orgao', $orgaos, null, ['placeholder' => 'Selecione um órgão', 'class' =>'form-control orgao-historico-unidade']) }}                    
        </div>
	
        <div class="col-md-6">
            {{ Form::select('id_unidade', [], null, ['placeholder' => 'Selecione uma unidade', 'class' =>'form-control estrutura-historico']) }}                    
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
