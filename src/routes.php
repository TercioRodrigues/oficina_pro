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

$router->get('/orcamentos/imprimir/{id}', 'OrcamentosController@Imprimir');