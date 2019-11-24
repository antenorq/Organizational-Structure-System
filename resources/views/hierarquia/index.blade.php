@extends('layouts.app')

@section('content')
	<fieldset>
	    <legend>HIERARQUIA</legend>
		
	    <div class="form-group">
	        <div class="col-md-4 content-orgaos">
	            {{ Form::label('id_orgao', 'Órgão:')}}
	            {{ Form::select('id_orgao', $orgaos, null, ['placeholder' => 'Selecione o órgão', 'class' =>'form-control orgao']) }}                    
	        </div>
	    </div>

		<div class="clear"></div>
	</fieldset>

	<hr/>

	<div class="content-hierarquia">
		<div align="center" class="title_unidades"><h4>UNIDADES</h4></div>			
		<table class="table table-hover">
			<tbody></tbody>
		</table>
		<span class="nao_encontrou"></span>
	</div>

@endsection