//EXECUTA A A��O PADR�O DE DELETE DA GRID LARAVEL VIA AJAX
//OBJETIVO DESSA C�DIGO � PARA N�O PRECISAR CRIAR ROTA PARA CADA DELETE E N�O CRIAR FORM ENVOLTA DOS BUTTONS DELETE, pois � necess�rio o method="DELETE" para funciona com a rota --resource
$(document).on( "click", ".delete", function(e)
{
    e.preventDefault();

    if($(this).hasClass("cargo"))
    	var result = window.confirm("Deseja realmente excluir? Caso exclua este cargo as suas atribui��es tamb�m ser�o exclu�das!");
    else
    	var result = window.confirm("Deseja realmente excluir?");

    if(result)
    {
    	$.ajax(
	    {
	        url:$(this).attr('href'),
	        method: "DELETE",
	        success: function(data)
			{
				var table = $('#table_id').DataTable();
				table.ajax.reload();
			}
	    });
    }

});


//CONFIGURA O TOKEN PARA FUNCIONAR A AÇÃO DELETE DO CLICK DELETE
$.ajaxSetup(
{
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});



