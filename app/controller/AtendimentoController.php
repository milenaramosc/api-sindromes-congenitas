<?php

namespace App\Controller;

use App\Core\Handlers\Response\ResponseHandler as Response;
use App\Model\Atendimento;

class AtendimentoController
{

  public function iniciar($json)
  {

    $data = json_decode($json);

    $atendimento = new Atendimento();
    $retorno = $atendimento->inserirProfissao($data);
    if ($retorno)
      return Response::getJson('S2A3-001', 200, ["payload" => json_decode($retorno)]);

    return Response::getJson('E2A3-001', 400);
    return $retorno;
  }
}
