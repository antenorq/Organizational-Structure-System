$("#cep").change(function()
{
	var result = $(this).val().split('-');
	var cep = result[0]+result[1];

	$.ajax(
	{
		url: "/consultaCEP",
		type: "POST",
		dataType: "json",
		data: "cep="+cep,
		success: function(data) {
			//$("#loading").hide();

			$("#logradouro").val(data.logradouro);
			$("#bairro").val(data.bairro);
		},
		error: function(e) {
			//$("#loading").hide();
			$("#logradouro").val("");
			$("#bairro").val("");
		}
	})
});

$("#cep").mask("99999-999");