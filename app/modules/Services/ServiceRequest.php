<?php

namespace Modules\Services;

use App\Core\Handlers\Curl\CurlHandler;

abstract class ServiceRequest
{
    /**
     * Url para realização da request
     *
     * @var string|null
     */
    protected ?string $url;

    /**
     * Url para gerar o token de autenticação
     *
     * @var string|null
     */
    protected ?string $authUrl;

    /**
     * Login para autenticação no serviço
     *
     * @var string|null
     */
    protected ?string $login;

    /**
     * Senha para autenticação no serviço
     *
     * @var string|null
     */
    protected ?string $password;

    /**
     * Token de acesso
     *
     * @var string|null
     */
    protected ?string $token;

    /**
     * Tempo para o token de acesso expirar
     *
     * @var string|null
     */
    protected ?string $expiresIn;

    /**
     * Ignorar o SSL
     *
     * @var boolean
     */
    protected bool $ignoreSsl;

    /**
     * HTTP status code de uma requisição realizada
     *
     * @var integer
     */
    protected int $serviceHttpCode;

    /**
     * Dados do response de uma request
     *
     * @var array|string
     */
    protected $serviceResponse;

    public function __construct(Service $service, bool $ignoreSsl = true)
    {
        //     $this->url       = $service->url;
        //     $this->authUrl   = $service->authUrl;
        //     $this->login     = $service->login;
        //     $this->password  = $service->password;
        //     $this->token     = $service->token;
        //     $this->expiresIn = $service->expiresIn;
        //     $this->ignoreSsl = $ignoreSsl;
    }

    /**
     * Realiza uma requisição GET ao serviço
     * @param string $endpoint
     * @param array $data
     * @param array $header
     * @return self
     */
    public function get(string $endpoint, array $data = [], array $header = [], array $extraCurlOpt = []): self
    {
        $this->request(
            'get',
            $endpoint,
            $data,
            array_merge($this->getRequestHeader(), $header),
            $extraCurlOpt
        );
        return $this;
    }

    /**
     * Realiza uma requisição POST ao serviço
     * @param string $endpoint
     * @param array|string $data
     * @param array $header
     * @return self
     */
    public function post(string $endpoint, $data = [], array $header = [], array $extraCurlOpt = []): self
    {
        $this->request(
            'post',
            $endpoint,
            $data,
            // array_merge($this->getRequestHeader(), $header),
            $header,
            $extraCurlOpt
        );
        return $this;
    }

    /**
     * Realiza uma requisição PUT ao serviço
     * @param string $endpoint
     * @param array|string $data
     * @param array $header
     * @return self
     */
    public function put(string $endpoint, $data = [], array $header = [], array $extraCurlOpt = []): self
    {
        $this->request(
            'put',
            $endpoint,
            $data,
            array_merge($this->getRequestHeader(), $header),
            $extraCurlOpt
        );
        return $this;
    }

    /**
     * Realiza uma requisição PATCH ao serviço
     * @param string $endpoint
     * @param array|string $data
     * @param array $header
     * @return self
     */
    public function patch(string $endpoint, $data = [], array $header = [], array $extraCurlOpt = []): self
    {
        $this->request(
            'patch',
            $endpoint,
            $data,
            array_merge($this->getRequestHeader(), $header),
            $extraCurlOpt
        );
        return $this;
    }

    /**
     * Realiza uma requisição DELETE ao serviço
     * @param string $endpoint
     * @param array|string $data
     * @param array $header
     * @return self
     */
    public function delete(string $endpoint, $data = [], array $header = [], array $extraCurlOpt = []): self
    {
        $this->request(
            'delete',
            $endpoint,
            $data,
            array_merge($this->getRequestHeader(), $header),
            $extraCurlOpt
        );
        return $this;
    }

    /**
     * Response de uma requisição no formato de array ou string
     *
     * @return array|string
     */
    public function serviceResponse()
    {
        return $this->serviceResponse;
    }

    /**
     * HTTP status code de uma requisição realizada
     *
     * @return integer
     */
    public function serviceHttpCode(): int
    {
        return $this->serviceHttpCode;
    }

    /**
     * Realiza uma requisição
     * @param string $method get|post|put|patch|delete
     * @param string $endpoint
     * @param array|string $data
     * @return self
     */
    protected function request(
        string $method,
        string $endpoint,
        $data,
        array $header = [],
        array $extraCurlOpt = []
    ): self {
        $curlHandler = new CurlHandler($header);

        $curlHandler->addOpt([
            // CURLOPT_SSL_VERIFYPEER => !$this->ignoreSsl,
            // CURLOPT_SSL_VERIFYHOST => !$this->ignoreSsl,
        ]);

        $curlHandler->addOpt($extraCurlOpt);

        // $response = $curlHandler->$method($this->url . $endpoint, $data);

        // $this->serviceResponse = $response['response'];
        // $this->serviceHttpCode = (int) $response['httpCode'];

        // $this->logRequest($this->url . $endpoint, $this->serviceHttpCode, $data, $this->serviceResponse, $header);

        return $this;
    }

    /**
     * Realiza uma requisição para autenticação
     * @param string $method get|post|put|patch|delete
     * @param string $endpoint
     * @param array|string $data
     * @return self
     */
    protected function authRequest(string $method, string $endpoint, $data, array $header = []): self
    {
        $curlHandler = new CurlHandler($header);

        $curlHandler->addOpt([
            // CURLOPT_SSL_VERIFYPEER => !$this->ignoreSsl,
            // CURLOPT_SSL_VERIFYHOST => !$this->ignoreSsl,
        ]);

        $response = $curlHandler->$method($this->authUrl . $endpoint, $data);

        // $this->serviceResponse = $response['response'];
        // $this->serviceHttpCode = (int) $response['httpCode'];

        $this->logRequest($this->authUrl . $endpoint, $this->serviceHttpCode, $data, $this->serviceResponse, $header);

        return $this;
    }

    /**
     * Prepara o header da request
     *
     * @return array
     */
    protected function getRequestHeader(): array
    {
        if (empty($this->token) || time() > strtotime($this->expiresIn)) {
            $this->token = $this->getNewToken();
        }

        return ['Authorization: Bearer ' . $this->token];
    }

    /**
     * Solicita um novo token de autenticação
     *
     * @return string
     */
    protected function getNewToken(): string
    {
        // Implemente o método na classe filha
        return '';
    }

    /**
     * Grava log das requests
     *
     * @param string $url
     * @param int $httpCode
     * @param string|array|null
     * @param string|array|null
     * @return void
     */
    protected function logRequest(string $url, int $httpCode, $request, $response, ?array $header = null): void
    {
        // Implemente o método na classe filha
    }
}
