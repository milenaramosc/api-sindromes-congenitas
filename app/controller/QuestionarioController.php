<?php

namespace App\Controller;

use App\Core\Handlers\Response\ResponseHandler as Response;
use App\Core\Helpers\ModelHelper;
use App\Model\Questionario;

class QuestionarioController
{
  public function enviarDadosQuestionario($json)
  {
    $dados = json_decode($json);
    // print_r($dados);
    // exit;
    $questionario = new Questionario();
    $retorno = $questionario->inserirDadosQuestionario($dados);
    if ($retorno)
      return Response::getJson('S2A3-001', 200, ["payload" => json_decode($retorno)]);

    return Response::getJson('E2A3-001', 400);
  }
}
