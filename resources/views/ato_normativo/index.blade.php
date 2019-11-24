@extends('layouts.app')

@section('content')

    @if(!empty($erro))
        {{ $erro }}
    @endif

    @if (session('status'))
            <div class="alert alert-danger">
            {{ session('status') }}
        </div>
    @endif

	<fieldset>
        <legend>LISTA DE ATOS NORMATIVOS</legend>

        <a href = "atonormativo/create"><button type="button" class="btn btn-primary">ADICIONAR</button><br><br></a>

        <table id="table_id" class="table table-striped table-bordered dataTable no-footer" width="100%" cellspacing="0" style="background-color: #fff;">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Tipo</th>
                    <th>Data</th>
                    <th>Data publicada</th>
                    <th>Caput</th>
                    <th>Situação</th>
                    <th class="width_acoes" style="width: 120px;">Ações</th>
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
                serverSide: true,
                ajax: '{!! route('atonormativo.dados') !!}',
                columns: [
                    { data: 'numero' },
                    { data: 'tipo.descricao' },
                    { data: 'data' },
                    { data: 'data_publicacao' },
                    { data: 'caput' },
                    { data: 'situacao.descricao' },
                    { data: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endsection
