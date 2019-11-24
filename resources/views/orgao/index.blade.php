@extends('layouts.app')

@section('content')

    @if (session('status'))
            <div class="alert alert-danger">
            {{ session('status') }}
        </div>
    @endif

    <fieldset>
        <legend>LISTA DE ÓRGÃOS</legend>

        <a href = "orgao/create"><button type="button" class="btn btn-primary" >ADICIONAR</button></a>
        <!--
        <a href = ""><button type="button" class="btn btn-default">DIVISÃO</button></a>
        <a href = ""><button type="button" class="btn btn-default">FUSÃO</button></a>
        -->
        <br><br>

        <table id="table_id" class="table table-striped table-bordered dataTable no-footer" width="100%" cellspacing="0" style="background-color: #fff;">
            <thead>
                <tr>
                    <th>Sigla</th>
                    <th>Descrição</th>
                    <th>Tipo Administração</th>
                    <th>Situação</th>
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
                "language": { "url": "{{ asset('js/linguagem_datatable_pt-br.json') }}"},
                processing: true,
                serverSide: false,
                ajax: '{!! route('orgao.dados') !!}',
                columns: [
                    { data: 'sigla' },
                    { data: 'descricao' },
                    { data: 'tipo_hierarquia.descricao'  },
                    { data: 'situacao.descricao' },
                    { data: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endsection
