<?php

namespace src;

class Config
{
    const BASE_DIR = '';

    const DB_DRIVER = 'mysql';
    const DB_HOST = 'localhost';
    const DB_DATABASE = 'oficina_db';
    const DB_USER = 'root';
    const DB_PASS = '#@cesso2020';

    const ERROR_CONTROLLER = 'ErrorController';
    const DEFAULT_ACTION = 'index';

    public static function setConfig()
    {
        date_default_timezone_set('America/Recife');
    }
}
