<?php

namespace App\Core\Handlers\Logs;

use App\Core\Utils\Files;
use App\Core\Utils\Str;
use DateTimeImmutable;

class LogHandler
{
    public const EMERGENCY = 'emergency';
    public const ALERT     = 'alert';
    public const CRITICAL  = 'critical';
    public const ERROR     = 'error';
    public const WARNING   = 'warning';
    public const NOTICE    = 'notice';
    public const INFO      = 'info';
    public const DEBUG     = 'debug';

    /**
     * Escreve em um arquivo de logs
     * @param string $fileName
     * @param string $content
     * @param bool $withDateTime
     * @return void
     */
    private static function writeLogFile($fileName, $content, $withDateTime = true)
    {
        $dirName  = ABSOLUTE_LOG_DIR . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR;
        $fileName = $dirName . $fileName;
        Files::createDir($dirName);

        if ($withDateTime) {
            $content = "[" . date('Y-m-d H:i:s') . "] " . $content;
        }

        Files::write($fileName, 'a', $content);
    }

    /**
     * Cria o arquivo de logs para requisições de serviços digitais
     * @param string $request
     * @param string $response
     * @return void
     */
    public static function logServicosDigitais($method, $url, $request, $response)
    {
        $content  = $method . " " . $url . "\t";
        $content .= Str::dataToJsonStr($request) . "\t";
        $content .= Str::dataToJsonStr($response) . "\n";
        self::writeLogFile('servicos_digitais.log', $content);
    }

    /**
     * Grava os logs das requisições realizadas ao micro serviço da Hubfintech
     * @param string $method
     * @param string $endpoint
     * @param mixed $request
     * @param mixed $response
     * @return void
     */
    public static function logHubfintechRequests(string $method, string $endpoint, $request, $response)
    {
        $content  = $method . " " . $endpoint . "\t";
        $content .= Str::dataToJsonStr($request) . "\t";
        $content .= Str::dataToJsonStr($response) . "\n";
        self::writeLogFile('hubfintech.log', $content);
    }

    /**
     * @param \Throwable  $thrown
     * @param string|null $sql
     * @param array|null  $params
     * @param bool|null   $strictTypes
     *
     * @return void
     */
    public static function logDbQuery(\Throwable $thrown, ?string $sql, ?array $params, ?bool $strictTypes): void
    {
        $content  = str_replace(array("\r", "\n"), "", $sql) . "\t";
        $content .= Str::dataToJsonStr($params) . "\t";
        $content .= $strictTypes . "\t";
        $content .= $thrown->getMessage() . "\n";
        self::writeLogFile('pdoExceptions.log', $content);
        \error_log("{$thrown->getMessage()} in {$thrown->getFile()}:{$thrown->getLine()}", 0);
    }

    /**
     * Cria o arquivo de logs para as exceptions do sistema
     * @param array $request
     * @return void
     */
    public static function logExceptions(string $messageCode, int $statusCode, \Throwable $thrown): void
    {
        self::writeLogFile(
            'exceptions.log',
            date("Y-m-d H:i:s") . " -> $messageCode $statusCode {$thrown->getMessage()}\n$thrown \n-\n",
            false
        );
    }

    /**
     * Cria o arquivo de logs para as requisições curl
     *
     * @param integer $userId
     * @param string $data
     * @param string $response
     * @param integer $httpCode
     * @param string $curlError
     * @param string $curlInfo
     * @return void
     */
    public static function logCurlRequests(
        string $url,
        string $data,
        string $header,
        string $response,
        int $httpCode,
        string $curlError,
        string $curlInfo
    ) {
        $content  = $url . "\t";
        $content .= $httpCode . "\t";
        $content .= Str::dataToJsonStr($data) . "\t";
        $content .= Str::dataToJsonStr($header) . "\t";
        $content .= Str::dataToJsonStr($response) . "\t";
        $content .= $curlError . "\t";
        $content .= Str::dataToJsonStr($curlInfo) . "\n";
        self::writeLogFile('curlRequests.log', $content);
    }

    public static function logHandshakeBmp(
        string $request,
        string $headers,
        string $response,
        int $statusCode
    ): void {
        self::writeLogFile(
            'bmpHandshake.log',
            $statusCode . "\t"
                . $_SERVER['REMOTE_ADDR'] . "\t"
                . Str::dataToJsonStr($request) . "\t"
                . Str::dataToJsonStr($headers) . "\t"
                . Str::dataToJsonStr($response) . "\n"
        );
    }

    public static function logFirebasePushNotifications(string $level, $message, array $context = []): void
    {
        $date = new DateTimeImmutable();

        self::writeLogFile(
            'firebasePushNotifications.log',
            strtoupper($level)
                . " "
                . $date->format("Y-m-d\TH:i:s.u")
                . " "
                . self::interpolate($message, $context)
                . \PHP_EOL,
            false
        );
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param string $message
     * @param array  $context
     *
     * @link https://www.php-fig.org/psr/psr-3
     *
     * @return string
     */
    private function interpolate(string $message, array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (
                !is_array($val)
                && (!is_object($val) || method_exists($val, '__toString'))
            ) {
                $replace['{' . $key . '}'] = (string) $val;
            }
        }

        return strtr($message, $replace);
    }
}
