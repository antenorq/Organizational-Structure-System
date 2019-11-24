$(document).ready(function() {
	//BUSCA O HISTÓRICO DA ESTRUTURA SELECIONADA (ÓRGÃO, UNIDADE, CONSELHO)
	$(".estrutura-historico").change(function() {
		var estrutura = $(this).val();

		$(".content-historico tbody").empty();
		$(".content-historico").show();

		$.ajax({
			url: "buscar-historico",
			type: "GET",
			dataType: "json",
			data: {estrutura: estrutura},
			success: function(data)
			{
				for(var i = 0; i < data.length; i++)
				{ 
					/*
					if(data[i].data == ''){data[i].data = '-'}
					if(data[i].sigla == ''){data[i].sigla = '-'}
					if(data[i].descricao == ''){data[i].data = '-'}
					if(data[i].data[i].tipo_acao_ato_normativo.descricao == ''){data[i].tipo_acao_ato_normativo.descricao = '-'}
					if(data[i].ato == ''){data[i].ato = '-'}
					*/

					$(".content-historico tbody").append("<tr><td>"+data[i].data+"</td><td>"+data[i].sigla+"</td><td>"+data[i].descricao+"</td><td>"+data[i].tipo_acao_ato_normativo.descricao+"</td><td>"+data[i].ato+"</td></tr>");
					//TERIA A PARTE DE COMPETENCIA E FINALIDADE CASO EXISTISSE NO ANTIGO SIGEO - <td><i class='glyphicon glyphicon-search detalhes' title='Competência: "+data[i].competencia.stripHTML()+" / Finalidade: "+data[i].finalidade.stripHTML()+"'></td>
				}
			}
		});
	});

	//EM HISTÓRICO UNIDADE - BUSCA AS UNIDADES DO ÓRGÃO SELECIONADO 
	$(".orgao-historico-unidade").change(function() {
		var orgao = $(this).val();

		$(".content-historico tbody").empty();
		$(".estrutura-historico").empty();
		$(".estrutura-historico").append('<option value=#>Selecione uma unidade');

		$.ajax({
			url: "/unidades-por-orgao",
			type: "GET",
			dataType: "json",
			data: {id_orgao_unidade: orgao},
			success: function(data) {
				$.each(data, function(key, value) {
					$(".estrutura-historico").append('<option value='+value.id+'>'+value.sigla+' - '+value.descricao+'</option>');
				});
			}
	    });
	});

	//EM HISTÓRICO ATO NORMATIVO - BUSCA O HISTÓRICO DO ATO NORMATIVO RELACIONADO AO ÓRGÃO SELECIONADO
	$('.historico-ato-orgao').change(function() {
		var orgao = $(this).val();

		$(".content-historico tbody").empty();
		$(".content-historico").show();

		$.ajax({
			url: "buscar-historico-ato",
			type: "POST",
			dataType: "json",
			data: {estrutura: orgao},
			success: function(data) {				
				for(var i = 0; i < data.length; i++) {
					$(".content-historico tbody").append("<tr><td>"+data[i].data+"</td><td>"+data[i].ato+"</td><td>"+data[i].tipo_acao_ato_normativo+"</td><td>"+data[i].data_publicacao+"</td><td><a href='"+data[i].ato_normativo.caminho_documento+"' target='blank'>EXIBIR DOCUMENTO</a></td></tr>");
				}
			}
		});

		//AO SELECIONAR O ÓRGÃO, BUSCA TODAS AS UNIDADES PERTENCENTES AO ÓRGÃO
		$.ajax({
			url: "/unidades-por-orgao",
			type: "GET",
			dataType: "json",
			data: {id_orgao_unidade: orgao},
			success: function(data) {
				$(".historico-ato-unidade").empty();
				$(".historico-ato-unidade").append('<option value=#>Selecione uma unidade');

				$.each(data, function(key, value) {
					$(".historico-ato-unidade").append('<option value='+value.id+'>'+value.sigla+' - '+value.descricao+'</option>');
				});
			}
		});
	});

	//EM ATO NORMATIVO - BUSCA O HISTÓRICO DO ATO NORMATIVO RELACIONADO A UNIDADE SELECIONADA
	$(".historico-ato-unidade").change(function() {
		var unidade = $(this).val();

		$(".content-historico tbody").empty();
		$(".content-historico").show();

		$.ajax({
			url: "buscar-historico-ato",
			type: "POST",
			dataType: "json",
			data: {estrutura: unidade},
			success: function(data) {
				for(var i = 0; i < data.length; i++) {
					$(".content-historico tbody").append("<tr><td>"+data[i].data+"</td><td>"+data[i].ato+"</td><td>"+data[i].tipo_acao_ato_normativo+"</td><td>"+data[i].data_publicacao+"</td><td><a href='"+data[i].ato_normativo.caminho_documento+"' target='blank'>EXIBIR DOCUMENTO</a></td></tr>");
				}
			}
		});
	});

	$(document).on('mouseover', '.detalhes', function() {
		$(document).tooltip();
	});

	//Retira as tags 
	String.prototype.stripHTML = function() {return this.replace(/<.*?>/g, '');}
});

