@extends('layouts.app')

@section('content')

    @if (session('status'))
            <div class="alert alert-danger">
            {{ session('status') }}
        </div>
    @endif

    <fieldset>
        <legend>LISTA DE ÓRGÃO COLEGIADO</legend>

        <a href = "orgaocolegiado/create"><button type="button" class="btn btn-primary">ADICIONAR</button><br><br></a>

        <table id="table_id" class="table table-striped table-bordered dataTable no-footer" width="100%" cellspacing="0" style="background-color: #fff;">
            <thead>
                <tr>
                    <th>Sigla</th>
                    <th>Descrição</th>
                    <th>Função</th>
                    <th>Situação</th>
                    <th>Órgão</th>
                    <!--<th>Ação Ato</th>-->
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
                ajax: '{!! route('orgaocolegiado.dados') !!}',
                columns: [
                    { data: 'sigla' },
                    { data: 'descricao' },
                    { data: 'funcao.descricao'  },
                    { data: 'situacao.descricao' },
                    { data: 'orgao_colegiado_rel.sigla' },
                    //{ data: 'tipo_acao_ato_normativo.descricao' },
                    { data: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endsection
