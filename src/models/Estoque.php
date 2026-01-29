<?php

namespace src\models;

use \core\Model;


class Estoque extends Model
{

    public static function estoque_minimo()
    {
        return self::$pdo->query("SELECT COUNT(*) FROM estoque WHERE quantidade <= estoque_minimo AND empresa_id = {$_SESSION['empresa_id']}")->fetchColumn() ?? 0;
    }
}
