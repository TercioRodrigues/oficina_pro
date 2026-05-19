<?php

namespace src\controllers;

use \core\Controller;

class VersaoController extends Controller
{
    public function index()
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        // Diretórios que deseja cachear
        $dirs = [
            '/assets/css',
            '/assets/images',
        ];

        echo json_encode([
            'version' => VERSAO
        ]);
    }
}
