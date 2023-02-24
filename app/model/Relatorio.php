<?php

namespace App\Model;

use App\Core\Helpers\ModelHelper;
use PDO;
use PDOException;

class Questionario extends ModelHelper
{
  private static $conexao;

  public function __construct()
  {
    parent::__construct();
    self::$conexao = $this->pdo;
  }

  public function getMalFormacaoEncontradasNoAtendimento($json){
    $json = 34;

    $sql = "SELECT";
  }
}
