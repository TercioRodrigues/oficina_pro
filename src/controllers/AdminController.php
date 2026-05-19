<?php

namespace src\controllers;

use \core\Controller;
use src\models\Login;

class AdminController extends Controller
{
    private bool $UsuarioLogado;
    public function __construct()
    {
        $this->UsuarioLogado = Login::verificarLogin();
        if (!$this->UsuarioLogado) {
            $this->redirect('/login');
            exit;
        }
    }
}
