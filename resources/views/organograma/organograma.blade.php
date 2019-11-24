<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    	<link href="{{ asset('css/index.css') }}" rel="stylesheet">

        <title>ORGANOGRAMA {{ $sigla_orgao_pai }}</title>

        {!! Charts::assets() !!}


    </head>
    <body>
        
        	<div style="float: left;margin-left: 10px;margin-top: 10px;"><img src="{{ asset('images/logo_pms.jpg') }}" width="" height="80px" /></div>
        	<div style="float: left;"><br><br> ESTRUTURA ORGANIZACIONAL - {{ $sigla_orgao_pai }}</div>
        	<br><br>
        	<div class="clear"></div>

            <center>              	 
                {!! $chart->render() !!}
            </center>
            <br><br><br>
            
            <div id="area_legenda">
                <div id="leg_vinculacao"></div><span class="legenda">Administração Indireta</span><div class="clear"></div>
                <div id="leg_orgaos_colegiados"></div><span class="legenda">Colegiado de Deliberação Superior</span><div class="clear"></div>
                <div id="leg_assessoria"></div><span class="legenda">Assessoria</span><div class="clear"></div>
                <!-- div id="leg_unidades_pai"></div><span class="legenda">Diretorias</span><div class="clear"></div -->
                <div id="leg_subordinacao"></div><span class="legenda">Subordinação Administrativa</span><div class="clear"></div>                     
            </div>

            <div class="clear"></div>

            <!-- PARAMETROS RETIRA_BOTOES QUE VEM DE PDF.BLADE -->
            @if(!isset($retira_botoes))
                <div style="float: right;margin-right: 50px;margin-top: 20px;"><a href="{{ URL::route('organograma.index') }}" class="btn btn-primary hide_print">VOLTAR</a></div>
                <div style="float: right;margin-right: 10px;margin-top: 20px;"><a onclick="javascript:window.print()" class="btn btn-primary hide_print">IMPRIMIR</a></div>
            @endif
        

    </body>
</html>