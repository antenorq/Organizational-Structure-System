<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<style>
		html, body, #geral
		{
			margin:0px;
			padding:0px;
			font-family:Arial;
			font-size: 14px;				
		}
		#geral
		{
			width:710px;
			text-align:left;
			margin-left:90px;			
		}
		#fontepms
		{
			font-size: 18px;
			margin-top:10px;
		}
		#ato_numero_data
		{
			text-transform: uppercase;
			text-align: center;
			margin-top: 40px;
			font-size: 16px;
		}
		#caput
		{
			float: right;
		    margin-top: 50px;
		    text-align: justify;
		    text-transform: uppercase;
		    width: 383px;
		    margin-bottom: 30px;
		}
		#rodape
		{
			text-align:left;
			width:625px;					
			position: relative;			
			height: 50px;
			clear:both;
		}
		.clear
		{
			clear: both;
		}		

		@media print
		{
			#botao_imprimir{display:none;}
		}

		p
		{
			margin:0px;
			padding: 0px;
		}
		
	</style>
	
</head>
<body>
<center>
	<div id="geral">
		<!-- CABEÇALHO -->		
		<br>
		<center><img src="{{ asset('images/pombapms.png') }}" width="100px" /></center>		
		<center><div id="fontepms"><B>PREFEITURA MUNICIPAL DE SALVADOR</B></div></center>
		<center><B>GABINETE DO PREFEITO</B></center>
		
		<div id="ato_numero_data">{{$ato->tipo}} {{$ato->numero}} DE {{$ato->data}}</div>

		<div id="caput"><b>{{$ato->caput}}</b></div>

		<div class="clear"></div>

		<div id="introducao">{{$ato->introducao}}</div>
		<br>
		<!-- DESCRIÇÃO DO CONTEÚDO -->		
		<div style="text-align:justify;">{!!$ato->conteudo!!}</div>
		<br>
		<br>		
		<br>		
		
		<input style="float:right" type="button" id="botao_imprimir" name="imprimir" value="Imprimir" onclick="window.print();">
		<a href="/painel"><input style="float:right" type="button" id="botao_imprimir" value="Voltar"></a>		
		<br>
		<br>
		<br>
	</div>
</center>
</body>
</html>