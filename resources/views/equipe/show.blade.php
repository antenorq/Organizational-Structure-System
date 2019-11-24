@extends('layouts.app')

@section('content')
	
	<div class="panel panel-default">
		<div class="title-show">GESTOR: {{$gestor->nome}}</div>
		<div class="foto-gestor"><img src="{{ asset($gestor->foto) }}"></div>
		<br/><br/><br/>
		<table class="table table-striped table-show">
		    <tbody>
		      <tr>
		        <td><b>Órgão</b>: {{$gestor->orgao}}<br/></td>
		      </tr>
		      <tr>
		        <td><b>Cargo</b>: {{$gestor->cargo}}<br/></td>
			  <tr>
	      	  	<td>
	      	  		<div class="content-equipe">
		      	  		<b>Equipe:</b>
		      	  		@foreach($equipe as $value)
		      	  		 	<b>{{$value->unidade}}</b> 
		      	  		 	<span>{{$value->nome}} - {{$value->cargo}}</span>
		      	  		@endforeach
	      	  		</div>
	      	   </tr>
	    	</tbody>
	  	</table>
	</div>
	
@endsection