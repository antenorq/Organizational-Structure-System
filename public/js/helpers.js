$(document).ready(function () {
	/* MASCARAS */
	$("#cnpj").mask("99.999.999/9999-99");
	$("#telefone").mask("(99) 9999-9999");
	$("#hora_inicio").mask("99:99");
	$("#hora_fim").mask("99:99");

	/* tinycem */
	tinymce.init({
		selector: '#competencia, #finalidade, #atribuicao, #conteudo',
		language: "pt_BR",
		menubar: "",
		plugins: "table hr advlist lists",
		advlist_bullet_styles: "square disc circle",  // only include square bullets in list
		toolbar: "undo redo | bold | italic | underline | bullist numlist outdent indent | forecolor backcolor | cut | removeformat | fontsizeselect | alignleft aligncenter alignright alignjustify | hr",
	});

	//NOS FORMS ONDE POSSUI SIGLA E DESCRIÇÃO, TODOS OS TEXTOS SERÃO CONVERTIDOS PARA MAIÚSCULO AO DIGITAR
	$("#sigla, #descricao").keyup(function () {
		$(this).val($(this).val().toUpperCase());
	});

	//REMOVE O BOTÃO EXCLUIR DOS CAMPOS DINÂMICOS DO 1º ITEM DA LISTA E FICA SÓ O ADD
	$(".remove:eq(0)").hide();

	//EM ÓRGÃO - NA MUDANÇA DO TIPO DE ADMINISTRAÇÃO (ADM DIRETA) ESCONDE A ÁREA DE RELAÇÃO HIERARQUICA E MOSTRA A ÁREA REPRESENTAÇÃO
	$("#id_tipo_hierarquia").change(function () {
		var id_tipo_hierarquia = $(this).val();

		//SE ADM DIRETA
		if (id_tipo_hierarquia == 1) {
			$("#relacao_hierarquia").hide();
			$("#representacao").show();
		}
		//SE QUALQUER OUTRO TIPO DE ADM
		else {
			$("#relacao_hierarquia").show();
			$("#representacao").hide();
		}
	})

	//EM ÓRGÃO - QUANDO JÁ VIER MARCADO ADM DIRETA ESCONDE A ÁREA DE RELAÇÃO HIERARQUICA SE FOR OUTRO TIPO ESCONDE REPRESENTAÇÃO
	var tipo_hierarquia = $("#id_tipo_hierarquia").val();
	if (tipo_hierarquia == 1) {
		$("#relacao_hierarquia").hide();
	}
	else {
		$("#representacao").hide();
	}


	//EM UNIDADE - O SELECT DE ORGÃOS FAZ CARREGAR AS UNIDADES DE SUBORDINAÇÃO DESSE ÓRGÃO SELECIONADO
	$('#id_orgao_unidade').change(function () {
		$.ajax(
			{
				url: '/unidades-por-orgao',
				data: 'id_orgao_unidade=' + $('#id_orgao_unidade option:selected').val(),
				success: function (data) {
					$('#id_unidade_subordinacao').html("<option value=''>Selecione</option>");
					$.each(data, function (index, element) {
						$('#id_unidade_subordinacao').append("<option value='" + element.id + "'>" + element.sigla + " - " + element.descricao + "</option>");
					});
				}
			});
	});

	//EM EQUIPE - BUSCA AS UNIDADES DO ORGÃO SELECIONADO
	$("#gestor").change(function () { getUnidadesDoOrgao(this); });

	//AUTO COMPLETE DO ATO NORMATIVO
	$(".ato_normativo").keyup(function () {
		const key = $(this).data('key');
		$("#id_ato_normativo_" + key).val("");
	});

	$(".ato_normativo").autocomplete({
		source: "/busca-atos",
		select: function (event, ui) {
			const key = $(this).data('key');
			//console.log(key, this, ui.item);
			$("#id_ato_normativo_" + key).val(ui.item.id);
		}
	});

	//DATEPICKER
	$("#data, #data_publicacao, #data_fim").datepicker({ changeMonth: true, changeYear: true, dateFormat: 'dd/mm/yy', yearRange: '1930:2017' });
	$("#data, #data_publicacao, #data_fim").prop("readonly", true).css("background", "white");

	//EM ÓRGÃO - AO EDITAR, VERIFICA SE O COMBO SITUAÇÃO APRESENTA TEMPORÁRIO, SE SIM EXIBE O CAMPO DATA FIM
	if ($("#id_sit_estr_organizacional").val() == 5 || $("#id_sit_estr_organizacional").val() == 4)
		$("#area_data_fim").show();

	//EM ÓRGÃO - CASO O COMBO SITUAÇÃO SEJA TEMPORÁRIO APRESENTA O CAMPO DATA FIM
	$("#id_sit_estr_organizacional").change(function () {
		if (this.value == 5 || this.value == 4) {
			$("#area_data_fim").show();
		}
		else {
			$("#area_data_fim").hide();
			$("#data_fim").val("");
		}
	});

	//AO ALTERAR SITUAÇÃO DA ESTRUTURA PARA EXTINTO, ALTERA O VALOR SELECIONADO DA AÇÃO DO ATO NORMATIVO PARA EXTINÇÃO 
	$("#id_sit_estr_organizacional").change(function () {
		if (this.value == 4)
			$("#id_tipo_acao_ato_normativo").val(7);
	});

	//AO ALTERAR AÇÃO DO ATO NORMATIVO PARA EXTINÇÃO ALTERA O VALOR DA SITUAÇÃO NO TOPO PARA EXTINTO TAMBÉM,   
	$("#id_tipo_acao_ato_normativo").change(function () {
		if (this.value == 7)
			$("#id_sit_estr_organizacional").val(4);
	});

	//EM HIERARQUIA - AO MUDAR O ÓRGÃO BUSCA TODAS AS UNIDADES DO ÓRGÃO ESCOLHIDO E LISTA EM FORMA DE HIRARQUIA
	$(".orgao").change(function () {
		var id_orgao = $(this).val();

		$(".content-hierarquia tbody").empty();
		$(".content-hierarquia .nao_encontrou").empty();

		$.ajax({
			url: "hierarquia/busca-hierarquia-orgao",
			type: "POST",
			dataType: "json",
			data: { orgao: id_orgao },
			success: function (data) {
				$(".title_unidades").show();
				$(".content-hierarquia table").show();
				$.each(data, function (key, value) {
					$(".content-hierarquia tbody").append("<tr><td>" + value + "</td></tr>");
				});
			},
			error: function (e) {
				$(".title_unidades").hide();
				$(".content-hierarquia table").hide();
				$(".content-hierarquia .nao_encontrou").html("Não há unidades cadastradas para este órgão");
			}
		});

	});


	//EM ATO NORMATIVO - MOSTRA OU ESCONDER A ÁREA DE CONTEÚDO OU DOCUMENTO
	$('#fl_tem_doc_sim').click(function () {
		$('#area_insercao_documento_ato').show();
		$('#area_elaboracao_ato_normativo').hide();
	});

	$('#fl_tem_doc_nao').click(function () {
		$('#area_elaboracao_ato_normativo').show();
		$('#conteudo_ifr').attr('style', 'height:500px; width:100%;');
		$('#area_insercao_documento_ato').hide();
	});

	if ($("#fl_tem_doc_sim").prop("checked") == true) {
		$('#area_insercao_documento_ato').show();
		$('#area_elaboracao_ato_normativo').hide();
	}
	if ($("#fl_tem_doc_nao").prop("checked") == true) {
		$('#area_elaboracao_ato_normativo').show();
		$('#conteudo_ifr').attr('style', 'height:500px; width:100%;');
		$('#area_insercao_documento_ato').hide();
	}


	//FIM DOCUMENT.READY DO JQUERY
});

function getUnidadesDoOrgao(element) {
	var selectedVal = $(".unidade").val();
	$("#unidade").empty();
	$("#unidade").attr('placeholder', 'Selecione a unidade');

	$.ajax({
		url: "/unidade/busca-unidades",
		type: 'POST',
		dataType: "json",
		data: { id_orgao: $(element).val(), tipo: 2 },
		success: function (data) {
			$("#unidade").append("<option value=''>Selecione a unidade</option>");

			$.each(data, function (key, value) {
				selected = "";
				if (selectedVal == key) {
					selected = "selected";
				}
				$("#unidade").append("<option " + selected + " value=" + key + ">" + value + "");
			});
		}
	});
}

// EM UNIDADE, CARGO E EQUIPE - AÇÃO DO BOTÃO VERDE ADD CAMPO DINÂMICO QUE FAZ O CLONE
$(document).on("click", ".add-icon", function () {
	var cssClass = $(this).data("container");
	$Container = $("." + cssClass).last().clone();
	$Container.data('route', '');
	$Container.find("select").val("");
	$Container.find("input").val("");
	$Container.insertAfter($("." + cssClass).last()).css("display", "inline");
	$(".remove:eq(1)").show();
	$(".add-icon:eq(1)").hide();

});

//EM UNIDADE, CARGO E EQUIPE - AÇÃO DO BOTÃO VERMELHO EXCLUIR CAMPO DINÂMICO
//Ao clicar no icone de remover atribuição, efetua requisição ajax para exclui-lo
$(document).on("click", ".remove", function () {
	var message = $(this).data('message');
	var url = $(this).data('route');
	var cssClass = $(this).data("container");

	var index = $("img.remove").index(this);
	var c = $("." + cssClass).eq(index);

	var excluir = window.confirm(message);

	if (excluir) {
		$.ajax({
			url: url,
			type: "DELETE",
			dataType: "json",
			success: function (data) {
				//c.remove();
			}
		}).always(function () {
			c.remove();
		});
		;
	}
});



