<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();
require '../vendor/autoload.php';
require '../src/routes.php';

$router->run( $router->routes );