<?php

namespace App\Model;


use App\Core\Helpers\ModelHelper;
use PDOException;

class Atendimento extends ModelHelper
{
  private static $conexao;

  private $id_atendimento;

  public function getId()
  {
    return $this->id_atendimento;
  }

  public function setId($id)
  {
    $this->id_atendimento = $id;
  }

  public function __construct()
  {
    parent::__construct();
    self::$conexao = $this->pdo;
  }
  public function inserirProfissao($json)
  {
    try {
      $localization = @unserialize(file_get_contents("http://ip-api.com/php"));

      if ($localization['status'] && $json->resposta) {
        $sql = "INSERT INTO atendimento (LOCALIZACAO, IP, PROFISSAO_USUARIO)
                        VALUES(:LOCALIZACAO,:IP,:PROFISSAO_USUARIO)
                ";
        $rQuery = self::$conexao->prepare($sql);
        $rQuery->bindValue(':LOCALIZACAO', $localization['city'] . ", " . $localization['regionName'] . ", " . $localization['country']);
        $rQuery->bindValue(':IP', $localization['query']);
        $rQuery->bindValue(':PROFISSAO_USUARIO', $json->resposta);
        $rQuery->execute();

        $idAtendimento = self::$conexao->lastInsertId();
        return json_encode(["id_atendimento" => $idAtendimento]);
      }
      return false;
    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
  }
}
