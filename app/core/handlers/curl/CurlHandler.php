<?php

namespace App\Core\Handlers\Curl;

use App\Core\Handlers\Logs\LogHandler;

class CurlHandler
{
    /**
     * Intância do CURL retornada no curl_init
     *
     * @var resource
     */
    private $curl;

    /**
     * Headers da requisição
     *
     * @var array
     */
    private array $header;

    /**
     * Opções do CURL
     *
     * @var array
     */
    private array $curlExtraOpt;

    /**
     * Url com endpoint para realizar a requisição
     *
     * @var string
     */
    private string $url;

    /**
     * Método HTTP da request
     *
     * @var string
     */
    private string $method;

    /**
     * Dados para enviar na requisição
     *
     * Se for um array, ele será encodado como json nos
     * métodos post, put, patch e delete. No método get
     * será formatado para query string.
     *
     * Se for uma string, será enviado como está no post fields
     * dos métodos post, put, patch e delete
     *
     * @var array|string
     */
    private $data;

    /**
     * Timeout padrão
     *
     * @var int
     */
    const DEFAULT_TIMEOUT = 0;

    public function __construct(array $header = [], array $curlExtraOpt = [])
    {
        $this->curl = curl_init();
        $this->header = $header;
        $this->curlExtraOpt = $curlExtraOpt;
    }

    /**
     * Realiza uma requisição get
     *
     * @param string $url
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function get(string $url, array $data = []): array
    {
        $this->url = $url;
        $this->data = $data;
        $this->method = 'GET';

        if (!empty($data)) {
            $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($this->curl, CURLOPT_URL, $url);

        return $this->request();
    }

    /**
     * Realiza uma requisição post
     *
     * @param string $url
     * @param array|string $data
     * @return array
     * @throws \Exception
     */
    public function post(string $url, $data = []): array
    {
        $this->url = $url;
        $this->data = $data;
        $this->method = 'POST';

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        $this->setPostFields($data);

        return $this->request();
    }

    /**
     * Realiza uma requisição put
     *
     * @param string $url
     * @param array|string $data
     * @return array
     * @throws \Exception
     */
    public function put(string $url, $data = []): array
    {
        $this->url = $url;
        $this->data = $data;
        $this->method = 'PUT';

        curl_setopt($this->curl, CURLOPT_URL, $url);
        $this->setPostFields($data);

        return $this->request();
    }

    /**
     * Realiza uma requisição patch
     *
     * @param string $url
     * @param array|string $data
     * @return array
     * @throws \Exception
     */
    public function patch(string $url, $data = []): array
    {
        $this->url = $url;
        $this->data = $data;
        $this->method = 'PATCH';

        curl_setopt($this->curl, CURLOPT_URL, $url);
        $this->setPostFields($data);

        return $this->request();
    }

    /**
     * Realiza uma requisição delete
     *
     * @param string $url
     * @param array|string $data
     * @return array
     * @throws \Exception
     */
    public function delete(string $url, $data = []): array
    {
        $this->url = $url;
        $this->data = $data;
        $this->method = 'DELETE';

        curl_setopt($this->curl, CURLOPT_URL, $url);
        $this->setPostFields($data);

        return $this->request();
    }

    /**
     * Adiciona um valor ao array de header
     *
     * @param array $array
     * @return void
     */
    public function addHeader(array $array): void
    {
        $this->header = array_merge($this->header, $array);
    }

    /**
     * Adiciona um valor ao array de opts
     *
     * @param array $array
     * @return void
     */
    public function addOpt(array $array): void
    {
        foreach ($array as $key => $value) {
            $this->curlExtraOpt[$key] = $value;
        }
    }

    /**
     * Realiza a requisição CURL
     *
     * @return array [
     *  curlInfo => retorno da função curl_getinfo
     * ]
     * @throws \Exception
     */
    private function request(): array
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_ENCODING, '');
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, self::DEFAULT_TIMEOUT);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);

        if (!empty($this->curlExtraOpt)) {
            foreach ($this->curlExtraOpt as $key => $value) {
                curl_setopt($this->curl, $key, $value);
            }
        }

        $response  = curl_exec($this->curl);
        $curlError = curl_error($this->curl);
        $curlInfo  = curl_getinfo($this->curl);
        $httpCode  = (int) $curlInfo['http_code'];
        curl_close($this->curl);

        LogHandler::logCurlRequests(
            "{$this->method}\t{$this->url}",
            json_encode($this->data),
            json_encode($this->header),
            $response,
            $httpCode,
            $curlError,
            json_encode($curlInfo)
        );

        return [
            "curlInfo" => $curlInfo,
            "httpCode" => $httpCode,
            "response" => $this->formatResponse($response),
        ];
    }

    /**
     * @param array|string $data
     * @return void
     */
    private function setPostFields($data): void
    {
        if (empty($data)) {
            return;
        }

        if (is_array($data)) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($data));
            return;
        }

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
    }

    /**
     * Formata o response
     *
     * @param array|string $response
     * @return void
     */
    private function formatResponse($response)
    {
        if ($response === false) {
            return $response;
        }

        if ($decoded = json_decode($response, 1)) {
            return $decoded;
        }

        return $response;
    }
}
