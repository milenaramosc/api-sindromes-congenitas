<?php

namespace App\Core\Handlers\Exceptions;

use App\Core\Exceptions\RBMException;
use App\Core\Exceptions\ServiceException;
use App\Core\Exceptions\TransacaoSpreadException;
use App\Core\Handlers\Logs\LogHandler;
use App\Core\Handlers\Response\ResponseHandler as Response;
use Throwable;

class ExceptionHandler
{
    protected Throwable $exception;
    protected string $messageCode;
    protected string $statusCode;
    protected Response $response;
    protected array $data;

    public function __construct(
        Throwable $exception,
        string $messageCode = "",
        int $statusCode = 500,
        array $data = []
    ) {
        $this->exception = $exception;
        $this->messageCode = $messageCode;
        $this->statusCode = $statusCode;
        $this->response = new Response();
        $this->data = $data;
    }

    /**
     * Exibe uma mensagem com os detalhes de uma exception
     *
     * @return void
     */
    public function print(): void
    {
        $payload = $this->details();

        $this->response->printJson(
            $this->messageCode,
            $this->statusCode,
            $payload
        );
    }

    private function details(): array
    {
        $details["dateTime"] = date('y-m-d H:i:s');
        $details["details"] = $this->prepareDetails($this->exception);

        if ($this->data !== []) {
            $details["data"] = $this->data;
        }

        if ($this->exception instanceof RBMException) {
            $this->statusCode = 400;
            $details["mensagem"] = trim($this->exception->getMessage());
        }

        if ($this->exception instanceof ServiceException) {
            $this->statusCode = 502;
        }

        if ($this->exception instanceof TransacaoSpreadException) {
            $this->statusCode = 502;
        }

        LogHandler::logExceptions($this->messageCode, $this->statusCode, $this->exception);

        if (AMBIENTE === 'prod') {
            unset($details['details']);
        }

        return $details;
    }

    private function prepareDetails(Throwable $thrown): array
    {
        $details = [
            "file"    => $thrown->getFile(),
            "line"    => $thrown->getLine(),
            "message" => $thrown->getMessage(),
            "code"    => $thrown->getCode(),
            "trace"   => $thrown->getTrace()
        ];

        if ($thrown->getPrevious() !== null) {
            $details['previous'] = $this->prepareDetails($thrown->getPrevious());
        }

        return $details;
    }
}
