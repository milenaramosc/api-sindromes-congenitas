<?php


namespace Modules\HtmlToPdf;


use Spipu\Html2Pdf\Html2Pdf;



use Modules\Services\Service;
use App\Core\Exceptions\ServiceException;




class HtmlToPdfConverter
{
    private bool $debug;
    private Request $request;

    public function __construct()
    {
        $this->debug = false;
        $this->request = new Request(new Service(21));
    }

    public function debug(): self
    {
        $this->debug = true;
        return $this;
    }

    public function convert(string $html)
    {

        $html2pdf = new Html2Pdf();
        $html2pdf->writeHTML($html);
        $html2pdf->output();
        // $requestBody = [
        //     "debug" => $this->debug,
        //     "options" => $options->get(),
        //     "origins" => [
        //         [
        //             "source" => base64_encode($html),
        //             "dictionary" => $dictionaryCollection->get()
        //         ]
        //     ]
        // ];

        // $response = $this->request
        //     ->post('/', $requestBody)
        //     ->serviceResponse();

        // if ($this->request->serviceHttpCode() !== 200) {
        //     throw new ServiceException("Não foi possível gerar o arquivo PDF");
        // }

        // return $response;
    }

    // public function convert(
    //     DictionaryCollection $dictionaryCollection,
    //     Options $options,
    //     string $html
    // ): string {
    //     $requestBody = [
    //         "debug" => $this->debug,
    //         "options" => $options->get(),
    //         "origins" => [
    //             [
    //                 "source" => base64_encode($html),
    //                 "dictionary" => $dictionaryCollection->get()
    //             ]
    //         ]
    //     ];

    //     $response = $this->request
    //         ->post('/', $requestBody)
    //         ->serviceResponse();

    //     if ($this->request->serviceHttpCode() !== 200) {
    //         throw new ServiceException("Não foi possível gerar o arquivo PDF");
    //     }

    //     return $response;
    // }
}
