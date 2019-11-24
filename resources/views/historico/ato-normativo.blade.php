@extends('layouts.app')

@section('content')

<fieldset>
    <legend>HISTÓRICO DO ATO NORMATIVO</legend>
	
    <div class="form-group">
        <div class="col-md-6">
            {{ Form::select('id_orgao', $orgaos, null, ['placeholder' => 'Selecione um órgão', 'class' =>'form-control historico-ato-orgao']) }}                    
        </div>

        <div class="col-md-6">
        	{{ Form::select('id_unidade', [], null, ['placeholder' => 'Selecione uma unidade', 'class' =>'form-control historico-ato-unidade']) }}                    
        </div>
    </div>

	<div class="clear"></div>

	<hr/>			

	<div class="content-historico">
		<table class="table table-striped">
			<thead>
				<th>DATA</th>
				<th>ATO NORMATIVO</th>
				<th>OPERAÇÃO</th>
				<th>DATA DE PUBLICAÇÃO</th>
				<th>DOCUMENTO</th>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<br/>
</fieldset>

<br/>
<div align="center">
	<a href="{{ URL::route('historico.index') }}" class="btn btn-primary">VOLTAR</a>
</div>
<br/>
@endsection