<?php

use core\Router;

$router = new Router();

$router->get('/', 'DashboardController@index');
$router->get('/dashboard', 'DashboardController@index');

$router->get('/login', 'LoginController@index');
$router->post('/login', 'LoginController@index');
$router->get('/logout', 'LoginController@logout');

$router->get('/orcamentos', 'OrcamentosController@index');
$router->get('/orcamentos/itens', 'OrcamentosController@orcamentoItens');
$router->post('/orcamentos/processar', 'OrcamentosController@processarAcoes');
$router->get('/orcamentos/procurarCliente/{cpf}', 'OrcamentosController@procurarClientePorCpf');

$router->get('/orcamentos/imprimir/{id}', 'OrcamentosController@Imprimir');

$router->get('/Os', 'OsController@index');
$router->get('/Os/itens', 'OsController@osItens');
$router->post('/Os/processar', 'OsController@processarAcoes');
$router->get('/Os/imprimir/{id}', 'OsController@imprimirOs');

$router->get('/agendamentos', 'AgendamentosController@index');
$router->post('/agendamentos/processar', 'AgendamentosController@ProcessarAcoes');

$router->get('/garantias', 'GarantiasController@index');


$router->get('/clientes', 'ClientesController@index');
$router->post('/clientes/processar', 'ClientesController@ProcessarAcoes');

$router->get('/veiculos', 'VeiculosController@index');
$router->get('/veiculos/historico/{id}', 'VeiculosController@historico');
$router->post('/veiculos/processar', 'VeiculosController@ProcessarAcoes');

$router->get('/servicos', 'ServicosController@index');
$router->post('/servicos/processar', 'ServicosController@ProcessarAcoes');

$router->get('/fornecedores', 'FornecedoresController@index');
$router->post('/fornecedores/processar', 'FornecedoresController@ProcessarAcoes');

$router->get('/estoque', 'EstoqueController@index');
$router->get('/estoque/categorias', 'EstoqueController@categorias');
$router->post('/estoque/processar', 'EstoqueController@ProcessarAcoes');

$router->get('/compras', 'ComprasController@index');
$router->get('/compras/itens/{id}', 'ComprasController@compraItens');
$router->post('/compras/processar', 'ComprasController@ProcessarAcoes');

$router->get('/caixa', 'CaixaController@index');
$router->post('/caixa/processar', 'CaixaController@ProcessarAcoes');

$router->get('/funcionarios', 'FuncionariosController@index');
$router->post('/funcionarios/processar', 'FuncionariosController@ProcessarAcoes');

$router->get('/configuracoes', 'ConfiguracoesController@index');
$router->post('/configuracoes/processar', 'ConfiguracoesController@ProcessarAcoes');

$router->get('/usuarios', 'UsuariosController@index');
$router->post('/usuarios/processar', 'UsuariosController@ProcessarAcoes');

$router->get('/painel', 'PainelController@index');
