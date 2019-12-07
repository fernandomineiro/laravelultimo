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
    return redirect("/login");
});

// Route::get('/', function () {
//     return redirect("/login/operadora");
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/operadora', 'OperadoraController@index')->name('operadora');
Route::match(['get', 'post'],'/operadora/listar', 'OperadoraController@listar')->name('operadora.listar');
Route::match(['get', 'post'],'/vaga/listar', 'VagaController@listar')->name('vaga.listar');
Route::get('/vaga/cadastrar', 'VagaController@cadastrar')->name('vaga.cadastro');
Route::post('/vaga/cadastrar', 'VagaController@store')->name('vaga.cadastro');
Route::post('/operadora', 'OperadoraController@store')->name('operadora');
Route::post('/operadora/alterar/{id}', 'OperadoraController@storeAlterar')->name('operadora.alterar');
Route::get('/operadora/alterar/{id}', 'OperadoraController@alterar')->name('operadora.alterar');
Route::get('/cidade/buscar', 'CidadeController@buscar')->name('cidade.buscar');
Route::get('/cidade/criar', 'CidadeController@criar')->name('cidade.criar');
Route::get('/estado/buscar', 'EstadoController@buscar')->name('estado.buscar');
Route::get('/bairro/criar', 'BairroController@criar')->name('bairro.criar');
Route::get('/bairro/buscar', 'BairroController@buscar')->name('bairro.buscar');

Route::get('/parametro', 'ParametroController@index')->name('parametro');
Route::match(['get', 'post'],'/parametro/listar', 'ParametroController@listar')->name('parametro.listar');

Route::post('/parametro/alterar/{id}', 'ParametroController@storeAlterar')->name('parametro.alterar');
Route::get('/parametro/alterar/{id}', 'ParametroController@alterar')->name('parametro.alterar');
Route::get('/parametro/registro', 'ParametroController@mostrar')->name('parametro.registro');
Route::post('/salvar', 'ParametroController@salvar');


Route::get('/sala/buscar/{id?}', 'SalaController@buscarPorUnidade')->name('sala.buscar.buscar-por-unidade');
Route::get('/tabela/buscar/{id?}', 'OperadoraController@buscarTabelaPorUnidade')->name('tabela.buscar.buscar-por-unidade');
Route::get('/especialidade/buscar/{id?}', 'EspecialidadeController@buscarPorSala')->name('especialidade.buscar.buscar-por-sala');


Route::get('/bairro/buscar/{id?}', 'BairroController@buscarPorCidade')->name('bairro.buscar.buscar-por-cidade');
Route::get('/estado/buscar-por-pais/{id?}', 'EstadoController@buscarPorPais')->name('estado.buscar.buscar-por-pais');
Route::get('/cidade/buscar-por-estado/{id?}', 'CidadeController@buscarPorEstado')->name('cidade.buscar.buscar-por-estado');


Route::get('/operador', 'OperadorController@index')->name('operador');
Route::match(['get', 'post'],'/operador/listar', 'OperadorController@listar')->name('operador.listar');
// Route::match(['get', 'post'],'/vaga/listar', 'VagaController@listar')->name('vaga.listar');
Route::get('/operador/cpf', 'OperadorController@autocompleteOperadorPeloCPF')->name('operador.cpf');
Route::get('/operador/unidades/{id}', 'OperadorController@buscarUnidades')->name('operador.unidades');
Route::post('/operador', 'OperadorController@store')->name('operador');
Route::post('/operador/alterar/{id}', 'OperadorController@storeAlterar')->name('operador.alterar');
Route::get('/operador/alterar/{id}', 'OperadorController@alterar')->name('operador.alterar');
Route::get('/vaga/acompanhamento/{idvaga}', 'VagaController@acompanhamento')->name('vaga.acompanhamento');
Route::get('/vaga/editar/{idvaga}', 'VagaController@editar')->name('vaga.acompanhamento.editar');
Route::post('/vaga/editar/{idvaga}', 'VagaController@editarSalvar')->name('vaga.acompanhamento.editar');
Route::get('/vaga/retornardadosmedico/{idmedico}/{idvaga}/{idtipocontratacao}', 'VagaController@retornarDadosMedico')->name('vaga.acompanhamento.retornar-dados-medico');
Route::get('/vaga/cancelarvaga/{id}', 'VagaController@cancelarVaga')->name('vaga.acompanhamento.cancelar-vaga');
Route::get('/vaga/removermedicovaga/{idmedico}/{idvaga}', 'VagaController@removerMedicoVaga')->name('vaga.acompanhamento.remover-medico-vaga');
Route::get('/vaga/aprovarmedicovaga/{idmedico}/{idvaga}', 'VagaController@aprovarMedicoVaga')->name('vaga.acompanhamento.aprovar-medico-vaga');
Route::post('/vaga/trocarmedicovaga/{idvaga}', 'VagaController@trocarMedicoVaga')->name('vaga.acompanhamento.trocar-medico-vaga');
Route::post('/vaga/trocarmedicoplantao/{idvaga}', 'VagaController@trocarMedicoPlantao')->name('vaga.acompanhamento.trocar-medico-plantao');
Route::get('/vaga/acompanhamento/plantoes/{idvaga}', 'VagaController@plantoesAjax')->name('vaga.acompanhamento.plantoes');

//foto
Route::post('/uploadOperador', 'OperadorController@upload')->name('uploadOperador');
Route::post('/uploadUnidade', 'UnidadeController@upload')->name('uploadUnidade');


Route::group(['prefix' => 'unidade'], function(){
    Route::post('/', 'UnidadeController@store')->name('unidade.cadastrar');
    Route::get('/', 'UnidadeController@index')->name('unidade');
    Route::match(['get', 'post'],'/listar', 'UnidadeController@listar')->name('unidade.listar');
    Route::post('/alterar/{id}', 'UnidadeController@storeAlterar')->name('unidade.alterar');
    Route::get('/alterar/{id}', 'UnidadeController@alterar')->name('unidade.alterar');    
    Route::post('/imagem/adicionar', 'UnidadeController@adicionarImagem')->name('imagem.adicionar');
    Route::post('/store-image', 'UnidadeController@storeImage')->name('unidade.storeImage');    
});

Route::get('/tabela-valor', 'TabelaValorController@index')->name('tabela-valor');
Route::match(['get', 'post'],'/tabela/listar', 'TabelaValorController@listar')->name('tabela-valor.listar');
Route::post('/tabela-valor', 'TabelaValorController@store')->name('tabela-valor');
Route::post('/tabela-valor/alterar/{id}', 'TabelaValorController@storeAlterar')->name('tabela-valor.alterar');
Route::get('/tabela-valor/alterar/{id}', 'TabelaValorController@alterar')->name('tabela-valor.alterar');


Route::group(['prefix' => '/banco'], function(){
    Route::get('/', 'BancoController@index')->name('banco');
    Route::match(['get', 'post'], '/listar', 'BancoController@listar')->name('banco.listar');    
    Route::post('/cadastrar', 'BancoController@cadastrar')->name('banco.cadastrar');
    Route::get('/editar/{id}', 'BancoController@editar')->name('banco.editar');
    Route::put('/atualizar/{id}', 'BancoController@atualizar')->name('banco.atualizar');
});

Route::group(['prefix' => '/convenio'], function(){
    Route::get('/', 'ConvenioController@index')->name('convenio');
    Route::match(['get', 'post'], '/listar', 'ConvenioController@listar')->name('convenio.listar');
    Route::post('/cadastrar', 'ConvenioController@cadastrar')->name('convenio.cadastrar');
    Route::get('/editar/{id}', 'ConvenioController@editar')->name('convenio.editar');
    Route::put('/atualizar/{id}', 'ConvenioController@atualizar')->name('convenio.atualizar');
});

Route::group(['prefix' => '/especialidade'], function(){
    Route::get('/', 'EspecialidadeController@index')->name('especialidade');
    Route::match(['get', 'post'], '/listar', 'EspecialidadeController@listar')->name('especialidade.listar');
    Route::post('/cadastrar', 'EspecialidadeController@cadastrar')->name('especialidade.cadastrar');
    Route::get('/editar/{id}', 'EspecialidadeController@editar')->name('especialidade.editar');
    Route::put('/atualizar/{id}', 'EspecialidadeController@atualizar')->name('especialidade.atualizar');
});

Route::group(['prefix' => '/instituicao'], function(){
    Route::get('/', 'InstituicaoController@index')->name('instituicao');
    Route::get('/cidade/{id}', 'InstituicaoController@getCidade')->name('instituicao.cidades');
    Route::match(['get', 'post'], '/listar', 'InstituicaoController@listar')->name('instituicao.listar');
    Route::post('/cadastrar', 'InstituicaoController@cadastrar')->name('instituicao.cadastrar');
    Route::get('/editar/{id}', 'InstituicaoController@editar')->name('instituicao.editar');
    Route::put('/atualizar/{id}', 'InstituicaoController@atualizar')->name('instituicao.atualizar');
});


Route::group(['prefix' => '/feriado'], function(){
    Route::get('/', 'FeriadoController@index')->name('feriado');
    Route::match(['get', 'post'], '/listar', 'FeriadoController@listar')->name('feriado.listar');
    Route::post('/cadastrar', 'FeriadoController@cadastrar')->name('feriado.cadastrar');
    Route::get('/editar/{id}', 'FeriadoController@editar')->name('feriado.editar');
    Route::put('/atualizar/{id}', 'FeriadoController@atualizar')->name('feriado.atualizar');
});

Route::group(['prefix' => '/aviso'], function(){
    Route::get('/', 'AvisoController@index')->name('aviso');
    Route::match(['get', 'post'], '/listar', 'AvisoController@listar')->name('aviso.listar');
    Route::get('/operadoras/unidades/{id}', 'AvisoController@getUnidades')->name('aviso.unidade');
    Route::get('/operadoras/grupo_medico/{id}', 'AvisoController@getGrupoMedico')->name('aviso.grupo');
    
    Route::get('/modulos', 'AvisoController@getModulos')->name('aviso.modulos');
    Route::get('/operadoras/buscar', 'AvisoController@getOperadoras')->name('aviso.operadoras');
    Route::get('/operadoras/unidades', 'AvisoController@getTodasUnidadesAjax')->name('aviso.unidades');
    Route::get('/operadoras/grupos', 'AvisoController@getTodosGruposMedicosAjax')->name('aviso.grupos');
    
    Route::post('/cadastrar', 'AvisoController@cadastrar')->name('aviso.cadastrar');
    Route::get('/editar/{id}', 'AvisoController@editar')->name('aviso.editar');
    Route::get('/editar/operadoras/unidades/{id}', 'AvisoController@getUnidades')->name('aviso.unidade.editar');
    Route::get('/editar/operadoras/grupo_medico/{id}', 'AvisoController@getGrupoMedico')->name('aviso.grupo.editar');
    Route::put('/atualizar/{id}', 'AvisoController@atualizar')->name('aviso.atualizar');
});

Route::group(['prefix' => '/prospect'], function(){
    Route::get('/', 'ProspectController@index')->name('prospect');
    Route::match(['get', 'post'], '/listar', 'ProspectController@listar')->name('prospect.listar');
    Route::get('/status_prospect', 'ProspectController@buscarStatusAjax')->name('prospect.status.buscar');
    Route::get('/{id}', 'ProspectController@cadastroCriado')->name('prospect.cadastrado');
    Route::post('/cadastrar', 'ProspectController@cadastrar')->name('prospect.cadastrar');
    Route::get('/editar/{id}', 'ProspectController@editar')->name('prospect.editar');
    Route::put('/atualizar/{id}', 'ProspectController@atualizar')->name('prospect.atualizar');
});

Route::get('/comunicacao/cadastro', 'ComunicacaoController@cadastro')->name('comunicacao.cadastro');
Route::post('/comunicacao/cadastrar', 'ComunicacaoController@cadastrar')->name('comunicacao.cadastrar');
Route::get('/comunicacao_tipo/buscar', 'ComunicacaoTipoController@buscar')->name('comunicacao_tipo.buscar');

Route::get('/perfil', 'PerfilController@index')->name('perfil');
Route::match(['get', 'post'], '/perfil/listar', 'PerfilController@listar')->name('perfil.listar');
Route::get('/perfil/modulo/{id}', 'PerfilController@buscarPorModulo')->name('perfil.modulo.buscar');
Route::post('/perfil/acessos', 'PerfilController@cadastrar');
Route::post('/perfil', 'PerfilController@cadastrar')->name('perfil.cadastrar');
Route::get('/perfil/editar/{id}', 'PerfilController@editar')->name('perfil.editar');
Route::get('/perfil/acessos/{id}', 'PerfilController@getPerfilAcesso')->name('perfil.acessos');
Route::post('/perfil/atualizar/{id}', 'PerfilController@atualizar')->name('perfil.atualizar');


Route::get('/usuario', 'UsuarioController@index')->name('usuario');
Route::match(['get', 'post'], '/usuario/listar', 'UsuarioController@listar')->name('usuario.listar');
Route::get('/usuario/perfil/{id}', 'UsuarioController@buscarPerfis')->name('usuario.perfil');
Route::get('/usuario/perfil', 'UsuarioController@getPerfis')->name('usuario.perfis');
Route::get('/usuario/unidades/{id}', 'UsuarioController@getUnidades')->name('usuario.unidades');
Route::get('/usuario/cpf', 'UsuarioController@autocompleteUsuarioPeloCPF')->name('usuario.cpf');
Route::post('/usuario/upload', 'UsuarioController@upload')->name('usuario.upload');
Route::post('/usuario/cadastrar', 'UsuarioController@cadastrar')->name('usuario.cadastrar');
Route::get('/usuario/editar/{id}', 'UsuarioController@editar')->name('usuario.editar');
Route::put('/usuario/atualizar/{id}', 'UsuarioController@atualizar')->name('usuario.atualizar');

Route::group(['prefix' => 'tabela-valor'], function () {
    Route::get('/', 'TabelaValorController@index')->name('tabela-valor');
    Route::get('/unidades', 'TabelaValorController@getUnidades')->name('tabela-valor.unidades');
    Route::match(['get', 'post'], '/listar', 'TabelaValorController@listar')->name('tabela-valor.listar');
    Route::post('/cadastrar', 'TabelaValorController@cadastrar')->name('tabela-valor.cadastrar');
    Route::get('/editar/{id}', 'TabelaValorController@editar')->name('tabela-valor.editar');
    Route::put('/atualizar/{id}', 'TabelaValorController@atualizar')->name('tabela-valor.atualizar');
    Route::match(['get', 'post'], '/valores/listar', 'TabelaValorController@listarValores')->name('tabela-valor.valores');
    Route::get('/valores/buscar', 'TabelaValorController@buscarValor')->name('tabela-valor.buscar-valor');
    Route::post('/clonar/{idtabela}', 'TabelaValorController@clonarTabela')->name('tabela-valor.clonar');
});

Route::group(['prefix' => '/faq'], function () {
    Route::get('/', 'FaqController@listarPerguntas')->name('faq');
    Route::get('/cadastro', 'FaqController@index')->name('faq.cadastro');
    Route::match(['get', 'post'], '/listar', 'FaqController@listar')->name('faq.listar');
    Route::get('/acima/{id}', 'FaqController@up')->name('faq.acima');
    Route::get('/abaixo/{id}', 'FaqController@down')->name('faq.abaixo');
    Route::get('/modulos', 'FaqController@getModulos')->name('faq.modulos');
    Route::post('/cadastrar', 'FaqController@cadastrar')->name('faq.cadastrar');
    Route::get('/editar/{id}', 'FaqController@editar')->name('faq.editar');
    Route::put('/atualizar/{id}', 'FaqController@atualizar')->name('faq.atualizar');
});

Route::group(['prefix' => '/sala'], function () {
    Route::get('/', 'SalaController@index')->name('sala.cadastro');
    Route::match(['get', 'post'], '/listar', 'SalaController@listar')->name('sala.listar');
    Route::post('/cadastrar', 'SalaController@store')->name('sala.cadastrar');
    Route::get('/editar/{id}', 'SalaController@edit')->name('sala.editar');
    Route::put('/atualizar/{id}', 'SalaController@update')->name('sala.atualizar');
});

Route::group(['prefix' => 'plantao'], function () {
    Route::get('/escalas', 'PlantaoController@buscarEscalas')->name('plantao.escalas');
    Route::get('/status', 'PlantaoController@buscarstatus')->name('plantao.status');
    Route::get('/salas', 'PlantaoController@buscarSalas')->name('plantao.salas');
    Route::get('/unidades', 'PlantaoController@buscarUnidades')->name('plantao.unidades');
    Route::get('/especialidades', 'PlantaoController@buscarEspecialidades')->name('plantao.especialidades');
    Route::get('/recorrencias', 'PlantaoController@buscarRecorrencias')->name('plantao.recorrencias');
    Route::get('/medicos', 'PlantaoController@buscarMedicos')->name('plantao.medicos');
    Route::get('/todos', 'PlantaoController@buscarPlantoes')->name('plantao.todos');
    Route::get('/datas-horas-plantoes', 'PlantaoController@buscarDatasEHorasPlantoes')->name('plantao.data-hora');
    Route::match(['get', 'post'], '/listar', 'PlantaoController@listar')->name('plantao.listar');
});