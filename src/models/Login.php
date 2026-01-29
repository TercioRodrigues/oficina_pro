<?php

namespace src\models;

use \core\Model;
use src\models\Usuarios;
use src\models\Empresas;

class Login extends Model
{

    public static function verificarLogin()
    {
        if (!empty($_SESSION['usuario_id'])) {
            return true;
            exit;
        }
        return false;
    }

    public static function fazerLogin($email, $senha)
    {
        $resultado = false;
        $usuario = Usuarios::select()->where('email', $email)->where('ativo', 1)->execute();
        if (count($usuario) > 0) {
            if (password_verify($senha, $usuario[0]['senha'])) {
                $_SESSION['usuario_id'] = $usuario[0]['id'];
                $_SESSION['usuario_nome'] = $usuario[0]['nome'];
                $_SESSION['usuario_nivel'] = $usuario[0]['nivel'];
                $_SESSION['usuario_email'] = $usuario[0]['email'];
                $_SESSION['empresa_id'] = $usuario[0]['empresa_id'];

                $empresa = Empresas::select(['nome_fantasia'])->where('id', $usuario[0]['empresa_id'])->get();
                $_SESSION['empresa_nome'] = $empresa[0]['nome_fantasia'];

                Usuarios::update()->set('ultimo_acesso', date('Y-m-d H:i:s'))->where('id', $usuario[0]['id'])->execute();
                $resultado = true;
            }
        }
        return $resultado;
    }
}
