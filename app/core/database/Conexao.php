<?php

namespace App\Core\Database;

use App\Core\Handlers\Exceptions\ExceptionHandler;
use PDO;
use PDOException;

class Conexao
{
    protected static $db;
    protected static $dbSCM;

    private function __construct()
    {
        $db_host    = DB_HOST;
        $db_nome    = DB_NAME;
        $db_usuario = DB_USER;
        $db_senha   = DB_PASS;
        $db_driver  = "mysql";

        try {
            // Atribui o objeto PDO à variável $db.
            self::$db = new PDO("$db_driver:host=$db_host; dbname=$db_nome", $db_usuario, $db_senha);
            // Garante que o PDO lance exceções durante erros.
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Garante que os dados sejam armazenados com codificação utf8mb4.
            self::$db->exec('SET NAMES utf8mb4');
        } catch (PDOException $e) {
            (new ExceptionHandler($e, 'E000-003'))->print();
        }
    }

    public static function conexao()
    {
        # Garante uma única instância. Se não existe uma conexão, criamos uma nova.
        if (!self::$db) {
            new Conexao();
        }
        return self::$db;
    }

    public static function getJwtSecret()
    {
        return "abC123!";
    }
}
