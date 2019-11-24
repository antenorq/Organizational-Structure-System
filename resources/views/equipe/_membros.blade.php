<div class="row container-membro" style="display: inline;">
    <div class="form-group">
        <div class="col-md-4 {{ $errors->has('equipe.nomes.'.$key) ? 'has-error' :'' }}">
            {{ Form::label('nome', 'Nome') }}
            <div class="container-nome">
                <input type="text" name="equipe[nomes][]" id="nome" class="form-control" value="{{ old('equipe.nomes.'.$key) != null ? old('equipe.nomes.'.$key) : $membro->nome }}" autocomplete="off" style="margin-bottom: 1%">
            </div>
        </div>

        <div class="col-md-4 {{ $errors->has('equipe.unidades.'.$key) ? 'has-error' :'' }}">
            {{ Form::label('unidade', 'Unidade') }}
            <div class="container-unidade">
                {{ Form::select('unidade.'.$key, $unidades, $membro->id_unidade, ['class'=>'form-control unidade', 'id'=>'unidade', 'placeholder'=>'Selecione a unidade', 'name' => 'equipe[unidades][]', 'style'=>'margin-bottom: 1%']) }}
            </div>
        </div>

        <div class="col-md-4 {{ $errors->has('equipe.cargos.'.$key) ? 'has-error' :'' }}">
            {{ Form::label('cargo', 'Cargo') }}
            <div class="container-cargo">
                {{ Form::select('cargo.'.$key, $cargos, $membro->id_cargo, ['class'=>'form-control cargo', 'id'=>'cargo.'.$key ,'name' => 'equipe[cargos][]', 'style'=>'margin-bottom: 1%']) }}
                <img src="{{ asset('/images/delete.png') }}" class='remove' data-message="Deseja realmente excluir este membro da equipe?" data-route="{{ route('equipe.destroy', ['id' => $membro->id]) }}" data-container="container-membro" style='margin-left:3%'>
            </div>
        </div>
    </div>
</div>
