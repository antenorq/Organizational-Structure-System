<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

	if(Auth::check())
	{
		return view('/home');
	}
	
    return view('auth.login');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/* CONSULTA_CEP */
Route::post('consultaCEP', 'CepController@consultaCep');

/* SITUAÇÃO */
Route::post('/situacao/alterar-situacao', 'SituacaoController@update')->name('situacao.update');

/* ORGÃO */
Route::resource('orgao', 'OrgaoController');
Route::get('/dados-grid-orgao', 'OrgaoController@dadosGridOrgao')->name("orgao.dados");
Route::post('orgaos', 'OrgaoController@getOrgaos');

/* UNIDADE */
Route::resource('unidade', 'UnidadeController');
Route::get('/unidades-por-orgao', 'UnidadeController@getUnidadesPorOrgao');
Route::post('/unidade/busca-unidades', 'UnidadeController@buscaUnidades');
Route::post('/unidade/busca-unidade-orgao-extintos', 'UnidadeController@getUnidadeOrgaoExtintos');
Route::delete('/unidade/cargo/{id}', 'UnidadeController@destroyCargo')->name('unidade-cargo.destroy');
Route::get('/dados-grid-unidade', 'UnidadeController@dadosGridUnidade')->name("unidade.dados");

/* ÓRGÃO COLEGIADO */
Route::resource('orgaocolegiado', 'OrgaoColegiadoController');
Route::get('/dados-grid-orgaocolegiado', 'OrgaoColegiadoController@dadosGridOrgaoColegiado')->name("orgaocolegiado.dados");

/* CARGO */
Route::resource('cargo', 'CargoController');
Route::get('/dados-grid-cargo', 'CargoController@dadosGrid')->name("cargo.dados");
Route::delete('/orgao/cargo/{id}', 'CargoController@destroyOrgao')->name('orgao-cargo.destroy');
Route::post('/cargo/busca-cargos', 'CargoController@buscaCargos');

/* EQUIPE */
Route::resource('equipe', 'EquipeController');
Route::get('grid-equipe', 'EquipeController@gridEquipe')->name('equipe.dados');

/* ATO NORMATIVO */
Route::resource('atonormativo', 'AtoNormativoController');
Route::get('/dadosGridAtoNormativo', 'AtoNormativoController@dadosGrid')->name("atonormativo.dados");
Route::get('/busca-atos', 'AtoNormativoController@buscaAtos');
Route::get('/atonormativo/visualizacao/{id}', 'AtoNormativoController@visualizacao')->name("atonormativo.visualizacao");



/* PERFIL */
Route::post('perfis', 'PerfilController@getPerfis');

/* USUÁRIO */
Route::get('usuario/recuperar-senha', 'UsuarioController@recuperarSenha');
Route::post('usuario/update-password', 'UsuarioController@updatePassword');
Route::get('usuario/dados-usuario', 'UsuarioController@dadosUsuario')->name('usuario.dados-usuario');
Route::post('usuario/atualizar-dados-usuario', 'UsuarioController@atualizarDadosUsuario')->name('usuario.atualizar-dados');
Route::get('grid-usuario', 'UsuarioController@gridUsuario')->name('usuario.dados');
Route::resource('usuario', 'UsuarioController');

/* RELATÓRIO */
Route::resource('relatorio', 'RelatorioController');

/* HIERARQUIA */
Route::resource('hierarquia', 'HierarquiaController');
Route::post('hierarquia/busca-hierarquia-orgao', 'HierarquiaController@getHierarquiaOrgao');

/* ORGANOGRAMA */
Route::resource('organograma', 'OrganogramaController');
Route::post('organograma/busca-hierarquia-orgao', 'OrganogramaController@getHierarquiaOrgao')->name('organograma.show');

/* CADASTRO ORGANIZACIONAL */
Route::resource('cadastro-organizacional', 'CadastroOrganizacionalController');
Route::post('cadastro-organizacional/documento', 'CadastroOrganizacionalController@getCadastroOrganizacional')->name('cadastro-organizacional.show');

/* HISTÓRICO */
Route::get('historico/orgao', 'HistoricoController@orgao');
Route::get('historico/unidade', 'HistoricoController@unidade');
Route::get('historico/orgao-colegiado', 'HistoricoController@orgaoColegiado');
Route::get('historico/ato-normativo', 'HistoricoController@atoNormativo');
Route::get('historico/buscar-historico', 'HistoricoController@historico');
Route::post('historico/buscar-historico-ato', 'HistoricoController@historicoAto');
Route::resource('historico', 'HistoricoController');

