@extends('layouts.app')

@section('content')

    @if (session('status'))
            <div class="alert alert-danger">
            {{ session('status') }}
        </div>
    @endif

	<fieldset>
        <legend>LISTA DE CARGOS E FUNÇÕES</legend>

        <a href="cargo/create"><button type="button" class="btn btn-primary">ADICIONAR</button><br><br></a>

        <table id="table_id" class="table table-striped table-bordered dataTable no-footer" width="100%" cellspacing="0" style="background-color: #fff;">
            <thead>
                <tr>
                    <th>Cargo</th>
                    <th>Tipo</th>
                    <th>Quantidade</th>
                    <th>Grau</th>
                    <th>Situação</th>
                    <th class="width_acoes" style="width:220px;">Ações</th>
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
                ajax: '{!! route('cargo.dados') !!}',
                columns: [
                    { data: 'descricao' },
                    { data: 'tipo.descricao' },
                    { data: 'qtde' },
                    { data: 'grau' },
                    { data: 'situacao.descricao' },
                    { data: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endsection
