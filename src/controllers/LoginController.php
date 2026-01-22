<?php

namespace src\controllers;

use \core\Controller;
use src\models\Login;

class LoginController extends Controller
{
    public function index()
    {
        $erro = '';
        if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['senha']) && !empty($_POST['senha'])) {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = filter_input(INPUT_POST, 'senha');
            $login = Login::fazerLogin($email, $senha);
            if ($login) {
                $this->redirect('/dashboard');
                exit;
            } else {
                $erro = 'email ou senha invalidos';
            }
        }

        $dados = [
            'erro' => $erro ?? '',
            'login' => ['email' => $email ?? '', 'senha' => $senha ?? '']
        ];

        $this->render('login', $dados);
    }

    public function logout()
    {
        if (Login::verificarLogin()) {
            session_unset();
            session_destroy();
        }
        $this->redirect('/login');
    }
}
