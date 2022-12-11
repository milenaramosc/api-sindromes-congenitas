<?php

namespace App\Controller;


use App\Core\Handlers\Response\ResponseHandler as Response;
use Modules\ExibicaoRelatorio\ExibirRelatorio;

class RelatorioController
{
  public function gerar($json = null)
  {
    $exibirRelatorio = new ExibirRelatorio();
    $exibirRelatorio->show();
  }
  
}
