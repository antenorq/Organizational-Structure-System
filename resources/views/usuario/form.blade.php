<fieldset>
    <legend>DADOS DO USUÁRIO</legend>
    
    <div class="row">
        <div class="form-group">
            <div class="col-md-4">
                {{ Form::label('name', 'Nome') }}
                {{ Form::text('name', null, ['class' => 'form-control']) }}        
            </div>

              <div class="col-md-4">
                {{ Form::label('id_orgao', 'Órgão') }}
                {{ Form::select('id_orgao', $orgaos, null, ['class' => 'form-control', 'placeholder' => 'Selecione o órgão']) }}        
            </div> 

            <div class="col-md-4">
                {{ Form::label('id_perfil', 'Perfil') }}
                {{ Form::select('id_perfil', $perfils, null, ['class' => 'form-control', 'placeholder' => 'Selecione o perfil']) }}        
            </div>
        </div> 
    </div>
    
    <div class="row">
        <div class="form-group">
            <div class="col-md-4">
                {{ Form::label('e-mail') }}
                {{ Form::email('email', null, ['class' => 'form-control']) }}        
            </div>

            <div class="col-md-4">
                {{ Form::label('password', 'Senha') }}
                {{ Form::password('password', ['class' => 'form-control']) }}        
            </div>

            <div class="col-md-4">
                {{ Form::label('password_confirm', 'Confirmar senha') }}
                {{ Form::password('password_confirm', ['class' => 'form-control', 'autocomplete' => 'off']) }}
            </div>
        </div>    
    </div>
</fieldset>


