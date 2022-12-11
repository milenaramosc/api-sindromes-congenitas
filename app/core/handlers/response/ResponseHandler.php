<?php

namespace App\Core\Handlers\Response;

use App\Core\Handlers\Request\RequestHandler as Request;

class ResponseHandler
{
    /**
     * Retorna um json de uma mensagem
     * @author Jonas Vicente
     * @param string $cod Código da mensagem
     * @param int $httpCode
     * @param array $payload informações que possam ser necessárias
     * @return string
     */
    public static function json(string $cod, int $httpCode = 200, array $payload = []): string
    {
        header('Content-Type: application/json', true, $httpCode);
        return json_encode(self::getMessage($cod, $payload));
    }

    /**
     * Exibe uma mensagem em formato json já tratando o Content-Type e o status code do header da requisição
     * @author Jonas Vicente
     * @param string $cod Código da mensagem
     * @param int $httpCode
     * @param array $payload informações que possam ser necessárias
     * @return void
     */
    public static function printJson(string $cod, int $httpCode = 200, array $payload = []): void
    {
        echo self::json($cod, $httpCode, $payload);
        exit;
    }

    /**
     * Retorna um array com uma mensagem
     * @author Jonas Vicente
     * @param string $cod Código da mensagem
     * @param array $payload Array contendo informações que possam ser necessárias
     * @return array
     */
    public static function getMessage(string $cod, array $payload = []): array
    {
        return array_merge([
            'cod' => $cod,
            'mensagem' => Messages::getInstance()->getMessageByCode($cod),
            'endpoint' => Request::getInstance()->getUrl()
        ], $payload);
    }

    /**
     * @param string $cod
     * @param array $payload
     * @param integer $httpCode
     * @return void
     * @deprecated
     */
    public static function printMensagemJson(string $cod, array $payload = [], int $httpCode = 200)
    {
        self::printJson($cod, $httpCode, $payload);
    }

    /**
     * @param string $cod
     * @param array $payload
     * @param integer $httpCode
     * @return string
     * @deprecated
     */
    public static function getJsonMensagem(string $cod, array $payload = [], int $httpCode = 200)
    {
        return self::json($cod, $httpCode, $payload);
    }

    /**
     * @param string $cod
     * @param integer $httpCode
     * @param array $payload
     * @return string
     */
    public static function getJson(string $cod, int $httpCode = 200, array $payload = []): string
    {
        header('Content-Type: application/json', true, $httpCode);
        return json_encode(self::getMessage($cod, $payload));
    }

    /**
     * Retorna a mensagem de não implementada
     * @author Jonas Vicente
     * @return void
     */
    public static function notImplemented()
    {
        header('HTTP/1.0 501');
        exit;
    }

    /**
     * Altera o header de para o status code 204 (no content)
     * @author Jonas Vicente
     * @return void
     */
    public static function noContentSuccess()
    {
        header('HTTP/1.0 204');
    }

    public static function pdfFile(string $fileName, string $content): void
    {
        ResponseHeader::pdfFile("$fileName.pdf");
        echo $content;
    }
}
