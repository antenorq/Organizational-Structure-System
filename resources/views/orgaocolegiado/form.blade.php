<fieldset>
    <legend>DADOS DO ÓRGÃO COLEGIADO</legend>

    <div class="form-group">
        <div class="col-md-2 {{ $errors->has('id_funcao') ? 'has-error' :'' }}">
            {{ Form::label('id_funcao', 'Função')}}
            {{ Form::select('id_funcao', $funcoes, null, ['placeholder' => 'Selecione','class' =>  'form-control']) }}                    
        </div>

        <div class="col-md-2 {{ $errors->has('id_tipo_hierarquia') ? 'has-error' :'' }}">
            {{ Form::label('id_tipo_hierarquia', 'Tipo')}}
            {{ Form::select('id_tipo_hierarquia', $tiposHierarquia, null, ['placeholder' => 'Selecione','class' =>  'form-control']) }}                    
        </div>

        <div class="col-md-4">
            {{ Form::label('id_orgao_orgaocolegiado', 'Órgão Vinculado')}}
            {{ Form::select('id_orgao_orgaocolegiado', $orgaos, null, ['placeholder' => 'Selecione','class' =>  'form-control']) }}
        </div>

        <div class="col-md-2 {{ $errors->has('id_sit_estr_organizacional') ? 'has-error' :'' }}">
            {{ Form::label('id_sit_estr_organizacional', 'Situação')}}
            {{ Form::select('id_sit_estr_organizacional', $situacoes, null, ['placeholder' => 'Selecione','class' =>  'form-control']) }}                    
        </div>

        <div id="area_data_fim">
            <div class="col-md-2 {{ $errors->has('data_fim') ? 'has-error' :'' }}">
                {{ Form::label('data_fim', 'Data fim') }}
                {{ Form::text('data_fim', null, ['class'=>'form-control'])}}
            </div>    
        </div>
    </div>

    <div class="form-group" >
        <div class="col-md-12">
            <hr>
        </div>        
    </div>

    <div class="form-group">
        <div class="col-md-2 {{ $errors->has('sigla') ? 'has-error' :'' }}">
            {{ Form::label('sigla', 'Sigla')}}
            {{ Form::text('sigla', null, ['class' =>  'form-control']) }}
        </div>

        <div class="col-md-6 {{ $errors->has('descricao') ? 'has-error' :'' }}">
            {{ Form::label('descricao', 'Descrição')}}
            {{ Form::text('descricao', null, ['class' =>  'form-control']) }}
        </div>
    </div>

</fieldset>
<br>

<fieldset>
    <legend>COMPETÊNCIA E FINALIDADE</legend>

    <div class="form-group">
        <div class="col-md-6 {{ $errors->has('competencia') ? 'has-error' :'' }}">
            {{ Form::label('competencia', 'Competência')}}
            {{ Form::textarea('competencia', null, ['class' =>  'form-control','rows'=>'5']) }}                   
        </div>
        
        <div class="col-md-6 {{ $errors->has('finalidade') ? 'has-error' :'' }}">
            {{ Form::label('finalidade', 'Finalidade')}}
            {{ Form::textarea('finalidade', null, ['class' =>  'form-control','rows'=>'5']) }}
        </div>
    </div>         
</fieldset>

<br>
<!--  ATO NORMATIVO -->
@include('ato_normativo.form-escolha')

<br>
<br>
