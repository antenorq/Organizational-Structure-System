@section('js')
<?/*php
        //Em erros de validação, caso tenha adicionado novas atribuições, o campo é novamente clonado com os valores preenchidos
        if(old("qtde.1"))
        {
            $qtdes = old("qtde");
            $cargos = old("cargo");
            $output = '<script>$(document).ready(function(){';

            for($i = count($qtdes); $i > 1; $i--)
            {
                $output .= '$(".add-icon").after("<select class=\"form-control cargo\" name=cargo[]><option value=1>'.$cargos.'</option></select>");';
                $output .= '$(".add-icon").after("<input class=\"form-control qtde\" id=qtde name=qtde[] type=text value='.$qtdes[$i-1].'> <img src=/images/delete.png class=remove style=\"margin-left: 3px\">");';
            }

            $output .= '});</script>';
            echo $output;
        }*/
    ?>
@endsection


<fieldset>
    <legend>DADOS DA UNIDADE</legend>

    <div class="form-group">

        <div class="col-md-6 {{ $errors->has('id_orgao_unidade') ? 'has-error' :'' }}">
            {{ Form::label('id_orgao_unidade', 'Órgão')}}
            {{ Form::select('id_orgao_unidade', $orgaos, null, ['placeholder' => 'Selecione','class' =>  'form-control']) }}
        </div>

        <div class="col-md-2 {{ $errors->has('id_tipo_hierarquia') ? 'has-error' :'' }}">
            {{ Form::label('id_tipo_hierarquia', 'Tipo de Unidade')}}
            {{ Form::select('id_tipo_hierarquia', $tiposHierarquia, null, ['placeholder' => 'Selecione','class' =>  'form-control']) }}
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
            {{ Form::text('sigla', null, ['class' => 'form-control', 'autocomplete' => 'off']) }}
        </div>

        <div class="col-md-4 {{ $errors->has('descricao') ? 'has-error' :'' }}">
            {{ Form::label('descricao', 'Descrição')}}
            {{ Form::text('descricao', null, ['class' => 'form-control', 'autocomplete' => 'off']) }}
        </div>

        <div class="col-md-2 {{ $errors->has('telefone') ? 'has-error' :'' }}">
            {{ Form::label('telefone', 'Telefone')}}
            {{ Form::text('telefone', null, ['class' =>  'form-control']) }}
        </div>

        <div class="col-md-4 {{ $errors->has('email') ? 'has-error' :'' }}">
            {{ Form::label('email', 'E-mail')}}
            {{ Form::text('email', null, ['class' =>  'form-control']) }}
        </div>
    </div>
</fieldset>

<br/>

<fieldset id="relacao_hierarquia">
    <legend>VINCULAÇÃO</legend>

    <div class="form-group">
        <div class="col-md-6 {{ $errors->has('id_unidade_subordinacao') ? 'has-error' :'' }}">
            {{ Form::label('id_unidade_subordinacao', 'Unidade Subordinação')}}
            {{ Form::select('id_unidade_subordinacao', (isset($unidades)) ? $unidades : [],null, ['class' =>  'form-control']) }}
        </div>
    </div>
</fieldset>

<br>
<fieldset>
    <legend>CARGOS</legend>
    Adicionar vinculação de cargo ou função <img src="{{ asset('/images/add.png') }}" class="add-icon" data-container="cargo-container" style='margin-left: 8px'>
    <br/><br/>
    @foreach($cargosUnidade as $key => $cargoUnidade)
        @include('unidade._cargo')
    @endforeach
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

