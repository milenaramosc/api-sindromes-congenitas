<?php

namespace Modules\ExibicaoRelatorio;

use App\Core\Handlers\Response\ResponseHandler;
use App\Core\Utils\Files;
use Modules\HtmlToPdf\DictionaryCollection;
use Modules\HtmlToPdf\HtmlToPdfConverter;
use Modules\HtmlToPdf\Options;
use Modules\Template\Templates;

/**
 * Realiza a exibição de um boleto
 *
 * @author Milena Ramos <milena.ramos@ice.ufjf.br>
 */

class ExibirRelatorio
{
  // private BoletoToShow $relatorio;
  private DictionaryCollection $dictionaryCollection;
  public function __construct()
  {
    $this->dictionaryCollection = new DictionaryCollection();
  }
  public function show(): void
  {
    // $service = (new BoletoCashInFactory())->getInstance();
    // $this->boleto = $service->get($this->boleto);

    $options = new Options();
    $converter = new HtmlToPdfConverter();

    $html = Files::readFile(ABSOLUTE_BILLET_DIR . DIRECTORY_SEPARATOR . "relatorio.template");
    // echo $html;
    // exit;
    // $path = ABSOLUTE_BILLET_DIR . DIRECTORY_SEPARATOR . "relatorio.template";
    // $htmlRelatorio = new Templates($path);
    // $htmlRelatorio->show();
    // $options->setMargins(10, 10);
    // $this->prepareDictionary();

    // $pdf = $converter->convert($this->dictionaryCollection, $options, $html);
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=Relatorio_PIBIC.pdf");
    echo $converter->convert($html);
    // echo $pdf;
    //  $pdf = $pdf[0];

    // ResponseHandler::pdfFile("Relatorio_PIBIC", $pdf);
  }
}
