@extends('layouts.app')

@section('content')

    @if (session('status'))
            <div class="alert alert-danger">
            {{ session('status') }}
        </div>
    @endif

    <fieldset>
        <legend>LISTA DE UNIDADES</legend>

        <a href = "/unidade/create"><button type="button" class="btn btn-primary" >ADICIONAR</button></a>
        <!--
        <a href = ""><button type="button" class="btn btn-default">DIVISÃO</button></a>
        <a href = ""><button type="button" class="btn btn-default">FUSÃO</button></a>
        -->
        <p style="float: right; margin-top: 10px">Exibir unidades de órgãos extintos <input type="checkbox" class="check-unidade-orgaos-extintos"></p>
        <br><br>

        <table id="table_id" class="table table-striped table-bordered dataTable no-footer" width="100%" cellspacing="0" style="background-color: #fff;">
            
            <thead>
                <tr>
                    <th>Órgão da unidade</th>
                    <th>Sigla</th>
                    <th>Descrição</th>
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
            //AO CARREGAR GRID, EXIBE TODAS AS UNIDADES COM ÓRGÃOS NÃO EXTINTOS
            datatable("unidade-orgaos-nao-extintos");

            //SE ESTIVER MARCADO ÓRGÃOS EXTINTOS, EXIBE TODAS AS UNIDADES COM ÓRGÃOS EXTINTOS
            $(document).on('change', '.check-unidade-orgaos-extintos', function() {
                if($(this).is(':checked')) 
                    datatable();
                else
                    datatable("unidade-orgaos-nao-extintos"); 
            });
            
            //GERA O DATATABLE COM OS DADOS DE ACORDO COM O CHECK MARCADO
            function datatable(tipo = null) {
                $('#table_id').DataTable({
                    destroy: true,
                    "language": { "url": "{{ asset('js/linguagem_datatable_pt-br.json') }}"},
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: '{!! route('unidade.dados') !!}',
                        data: {
                            tipo: tipo,
                        }
                    },
                    columns: [
                        { data: 'orgao_unidade.sigla' },
                        { data: 'sigla' },
                        { data: 'descricao' },
                        { data: 'situacao.descricao' },
                        { data: 'action', orderable: false, searchable: false },
                    ],
                });
            }
        });
    </script>
@endsection
