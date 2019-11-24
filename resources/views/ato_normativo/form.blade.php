<fieldset>
	<legend>ATO NORMATIVO</legend>

	<div class="form-group">
		<div class="col-md-3 {{ $errors->has('id_tipo_ato_normativo') ? 'has-error' :'' }}">
			{{ Form::label('id_tipo_ato_normativo', 'Tipo') }}
			{{ Form::select('id_tipo_ato_normativo', $tipos_ato_normativo, null, ['class'=>'form-control', 'placeholder'=>'Escolha o tipo']) }}
		</div>

		<div class="col-md-2 {{ $errors->has('numero') ? 'has-error' :'' }}">
			{{ Form::label('numero', 'Número') }}
			{{ Form::text('numero', null, ['class'=>'form-control', 'maxlength'=>10]) }}
		</div>

		<div class="col-md-2 {{ $errors->has('data') ? 'has-error' :'' }}">
			{{ Form::label('data', 'Data do Ato Adm') }}
			{{ Form::text('data', null, ['class'=>'form-control']) }}
		</div>

		<div class="col-md-2 {{ $errors->has('data') ? 'has-error' :'' }}">
			{{ Form::label('data_publicacao', 'Data de Publicação') }}
			{{ Form::text('data_publicacao', null, ['class'=>'form-control']) }}
		</div>
	</div>

	<div class="form-group" >
        <div class="col-md-12">
            <hr>
        </div>        
    </div>

    <div class="form-group">
		<div class="col-md-12">
			{{ Form::label('caput', 'Caput') }}
			{{ Form::text('caput', null, ['class'=>'form-control']) }}
		</div>
	</div>

    <div class="form-group" >
        <div class="col-md-12">
            <hr>
        </div>        
    </div>

    <div class="form-group">
    	<div class='col-md-4'>
  			{{ Form::label('fl_tem_doc_sim', 'Documento já existe para esse Ato Normativo?') }}			
  		</div>
  		<div class='col-md-3'>  			
			{{ Form::label('fl_tem_doc_sim', 'Sim') }}  {{ Form::radio('fl_tem_doc', '1', '', ['id' => 'fl_tem_doc_sim']) }}
			{{ Form::label('fl_tem_doc_nao', 'Não') }}  {{ Form::radio('fl_tem_doc', '0', '', ['id' => 'fl_tem_doc_nao']) }}
  		</div>
  	</div>

  	<div class="form-group" >
        <div class="col-md-12">
            <hr>
        </div>        
    </div>

	<div id="area_insercao_documento_ato">
	    <div class="form-group">
			<div class="col-md-12 {{ $errors->has('documento') ? 'has-error' :'' }}">
				{{ Form::label('documento', 'Documento') }}<br/>
				{{ Form::file('documento', null) }}
			</div>
		</div>

		<div class="form-group" >
	        <div class="col-md-12">
	            <hr>
	        </div>        
	    </div>
	</div>

    <div id="area_elaboracao_ato_normativo">
	    <div class="form-group" > 
			<div class="col-md-12">
				{{ Form::label('introducao', 'Introdução') }}
				{{ Form::textarea('introducao', null, ['class'=>'form-control','rows'=>'3']) }}
			</div>
	    </div>

	    <div class="form-group" >
	        <div class="col-md-12">
	            <hr>
	        </div>        
	    </div>

	    <div class="form-group" > 
			<div class="col-md-12">
				{{ Form::label('conteudo', 'Conteúdo') }}
				{{ Form::textarea('conteudo', null, ['class'=>'form-control','rows'=>'20']) }}
			</div>
	    </div>

	    <div class="form-group" >
	        <div class="col-md-12">
	            <hr>
	        </div>        
	    </div>
	</div>

	<div class="form-group" > 
		<div class="col-md-12">
			{{ Form::label('obs_ato_normativo', 'Observação') }}
			{{ Form::text('obs_ato_normativo', null, ['class'=>'form-control']) }}
		</div>
    </div>

</fieldset>

<br/>



