@extends('layouts.app')

@section('content')

    @if (session('status'))
            <div class="alert alert-danger">
            {{ session('status') }}
        </div>
    @endif


	<fieldset>
        <legend>LISTA DE USUÁRIOS</legend>

        <a href="/usuario/create"><button type="button" class="btn btn-primary">ADICIONAR</button><br><br></a>

        <table id="table_id" class="table table-striped table-bordered dataTable no-footer" width="100%" cellspacing="0" style="background-color: #fff;">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Órgão</th>
                    <th>Perfil</th>
                    <th>Ações</th>
                </tr>
            </thead>
        </table>

    </fieldset>

@endsection

@section('js')
    <script>
        $( document ).ready(function()
        {
            $('#table_id').DataTable({
                "language": { "url": "{{ asset('js/linguagem_datatable_pt-br.json') }}"},
                processing: true,
                serverSide: false,
                ajax: '{!! route('usuario.dados') !!}',
                columns: [
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'orgao.sigla' },
                    { data: 'perfil.descricao' },
                    { data: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endsection
