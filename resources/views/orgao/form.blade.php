@section('js')
    <script src="{{ asset('js/consulta_cep.js') }}"></script>
@endsection

<fieldset>
    <legend>DADOS DO ÓRGÃO</legend>

    
    <div class="form-group">
        <div class="col-md-3 {{ $errors->has('id_funcao') ? 'has-error' :'' }}">
            {{ Form::label('id_funcao', 'Função')}}
            {{ Form::select('id_funcao', $funcoes, null, ['placeholder' => 'Selecione','class' =>  'form-control']) }}                    
        </div>

        <div class="col-md-3 {{ $errors->has('id_tipo_hierarquia') ? 'has-error' :'' }}">
            {{ Form::label('id_tipo_hierarquia', 'Tipo de Administração')}}
            {{ Form::select('id_tipo_hierarquia', $tiposHierarquia, null, ['placeholder' => 'Selecione','class' =>  'form-control']) }}                    
        </div>
        
        <div class="col-md-3 {{ $errors->has('id_sit_estr_organizacional') ? 'has-error' :'' }}">
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
            {{ Form::text('sigla', null, ['class' =>  'form-control', 'autocomplete'=>'off']) }}
        </div>

        <div class="col-md-5 {{ $errors->has('descricao') ? 'has-error' :'' }}">
            {{ Form::label('descricao', 'Descrição')}}
            {{ Form::text('descricao', null, ['class' =>  'form-control', 'autocomplete'=>'off']) }}
        </div>

        <div class="col-md-3 {{ $errors->has('cnpj') ? 'has-error' :'' }}">
            {{ Form::label('cnpj', 'CNPJ')}}
            {{ Form::text('cnpj', null, ['class' =>  'form-control']) }}                   
        </div>

        <div class="col-md-2 {{ $errors->has('telefone') ? 'has-error' :'' }}">
            {{ Form::label('telefone', 'Telefone')}}
            {{ Form::text('telefone', null, ['class' =>  'form-control']) }}                   
        </div>
    </div>

    <div class="form-group" >
        <div class="col-md-12">
            <hr>
        </div>        
    </div>

    <div class="form-group">       
        <div class="col-md-3 {{ $errors->has('email') ? 'has-error' :'' }}">
            {{ Form::label('email', 'E-mail')}}
            {{ Form::text('email', null, ['class' =>  'form-control']) }}
        </div>

        <div class="col-md-5 {{ $errors->has('site') ? 'has-error' :'' }}">
            {{ Form::label('site', 'Site')}}
            {{ Form::text('site', null, ['class' =>  'form-control']) }}
        </div>

        <div class="col-md-2 {{ $errors->has('hora_inicio') ? 'has-error' :'' }}">
            {{ Form::label('hora_inicio', 'Horário Início')}}
            {{ Form::text('hora_inicio', null, ['class' =>  'form-control']) }}                   
        </div>

        <div class="col-md-2 {{ $errors->has('hora_fim') ? 'has-error' :'' }}">
            {{ Form::label('hora_fim', 'Horário Fim')}}
            {{ Form::text('hora_fim', null, ['class' =>  'form-control']) }}                   
        </div>
    </div>

</fieldset>

<br>

<fieldset id="relacao_hierarquia">
    <legend>VINCULAÇÃO</legend>

    <div class="form-group">
        <div class="col-md-6 {{ $errors->has('id_orgao_vinculacao') ? 'has-error' :'' }}">
            {{ Form::label('id_orgao_vinculacao', 'Órgão')}}
            {{ Form::select('id_orgao_vinculacao', $orgaos, null, ['placeholder' => 'Selecione','class' =>  'form-control']) }}
        </div>      
    </div>         
</fieldset>

<fieldset id="representacao">
    <legend>REPRESENTAÇÃO</legend>

    <div class="form-group">
        <div class="col-md-6 {{ $errors->has('id_unidade_representacao') ? 'has-error' :'' }}">
            {{ Form::label('id_unidade_representacao', 'Representação PGMS')}}
            {{ Form::select('id_unidade_representacao', $unidades_representacao, null, ['placeholder' => 'Selecione','class' =>  'form-control']) }}
        </div>         
    </div>         
</fieldset>

<br>
<!--  ENDEREÇO -->
@include('endereco.form')

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

