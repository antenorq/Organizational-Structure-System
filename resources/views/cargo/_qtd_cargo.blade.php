<div class="form-group cargo-container">    
    <div class="col-md-6 {{ $errors->has('orgao.'.$key) ? 'has-error' :'' }}">
        {{ Form::label('orgao.'.$key, 'Órgão', ['class' => 'control-label']) }}
        <div class="container-cargo">
            {{ Form::select('orgao.'.$key, $orgaos, $cargoOrgao->id_orgao, ['class'=>'form-control orgao','name' => 'orgao[]', 'id' => 'orgao.'.$key, 'style'=>'margin-bottom: 3px','disabled'=> is_null($bt_atribuicoes) ? false : 'disabled']) }}
            @if($bt_atribuicoes == 1)
                {{ Form::hidden('orgao[]', $cargoOrgao->id_orgao) }}
            @endif            
        </div>
    </div>
    <div class="col-md-2 content-qtde {{ $errors->has('qtde_orgao.'.$key) ? 'has-error' :'' }}">
        {{ Form::label('qtde_orgao', 'Quant') }}
        <div class="container-qtde">
            <input type="text" name="qtde_orgao[]" id="qtde_orgao" class="form-control qtde" value="{{ old('qtde_orgao.'.$key) != null ? old('qtde_orgao.'.$key) : $cargoOrgao->qtde }}" autocomplete="off" style="margin-bottom: 3px;width:60px;float:left;"  @if(!is_null($bt_atribuicoes)) disabled="disabled" @endif>
            @if($bt_atribuicoes == 1)
            {{ Form::hidden('qtde_orgao[]', $cargoOrgao->qtde) }}
            @endif            
            
            @if(is_null($bt_atribuicoes))
                <img src="{{ asset('/images/delete.png') }}" data-message="Deseja realmente deletar?" data-key="" data-route="{{ route('orgao-cargo.destroy', ['id' => $cargoOrgao->id]) }}" data-container="cargo-container" class="remove" style='margin-left: 8px'>
            @endif
        </div>
    </div>

    @if($bt_atribuicoes == 1)        
        <div class="col-md-12 {{ $errors->has('atribuicao_generica.'.$key) ? 'has-error' :'' }}">
            {{ Form::label('atribuicao_generica.'.$key, 'Atribuições', ['class' => 'control-label']) }}
            <div class="container-cargo"> 
                {{ Form::textarea('atribuicao_generica[]', $cargoOrgao->atribuicao_generica, ['class' =>'form-control','rows'=>'5']) }}      
            </div>

            <!--  ATO NORMATIVO -->            
            @include('ato_normativo.form-escolha-atribuicoes')           
            <br>
            <hr>
            <br>
        </div>        
    @endif
    
</div>