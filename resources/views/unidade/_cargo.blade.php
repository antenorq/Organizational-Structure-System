<div class="form-group cargo-container">
    <div class="col-md-6 {{ $errors->has('cargo.'.$key) ? 'has-error' :'' }}">
        {{ Form::label('cargo.'.$key, 'Cargo comissionado ou função de confiança', ['class' => 'control-label']) }}
        <div class="container-cargo">
            {{ Form::select('cargo.'.$key, $cargos, $cargoUnidade->id_cargo, ['class'=>'form-control cargo','name' => 'cargo[]', 'id' => 'cargo.'.$key, 'style'=>'margin-bottom: 3px']) }}            
        </div>
    </div>
    <div class="col-md-2 content-qtde {{ $errors->has('qtde.'.$key) ? 'has-error' :'' }}">
        {{ Form::label('qtde', 'Quantidade') }}
        <div class="container-qtde">
            <input type="text" name="qtde[]" id="qtde" class="form-control qtde" value="{{ old('qtde.'.$key) != null ? old('qtde.'.$key) : $cargoUnidade->qtde }}" autocomplete="off" style="margin-bottom: 3px">
            <img src="{{ asset('/images/delete.png') }}" data-message="Deseja realmente deletar?" data-key="" data-route="{{ route('unidade-cargo.destroy', ['id' => $cargoUnidade->id]) }}" data-container="cargo-container" class="remove" style='margin-left: 8px'>
        </div>
    </div>
</div>