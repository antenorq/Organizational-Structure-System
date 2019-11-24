@extends('layouts.app')

@section('content')

    @if (session('status'))
            <div class="alert alert-danger">
            {{ session('status') }}
        </div>
    @endif

	<fieldset>
        <legend>LISTA DE GESTORES DA EQUIPE</legend>

        <a href="equipe/create"><button type="button" class="btn btn-primary">ADICIONAR</button><br><br></a>

        <table id="table_id" class="table table-striped table-bordered dataTable no-footer" width="100%" cellspacing="0" style="background-color: #fff;">
            <thead>
                <tr>
                    <th>Gestor</th>
                    <th>Órgão</th>
                    <th>Cargo</th>
                    <th class="width_acoes">Ações</th>
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
                destroy: true,
                "language": { "url": "{{ asset('js/linguagem_datatable_pt-br.json') }}"},
                processing: true,
                serverSide: true,
                ajax: '{!! route('equipe.dados') !!}',
                columns: [
                    { data: 'nome' },
                    { data: 'orgao.descricao' },
                    { data: 'cargo.descricao' },
                    { data: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endsection
