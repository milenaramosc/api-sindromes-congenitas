<?php

/**
 * Require message files
 *
 * @return void
 */
function requireMessagesFiles(): void
{
    recursiveRequire('app/messages/');
}

/**
 * Require route files
 *
 * @return void
 */
function requireRouteFiles(): void
{
    recursiveRequire('app/routes/');
}

/**
 * Inclui os arquivos recursivamente em uma determinada pasta
 *
 * @param string $dir
 * @return void
 */
function recursiveRequire(string $dir): void
{
    foreach (new \DirectoryIterator($dir) as $f) {
        if (!$f->isDot()) {
            $f->isDir() ? recursiveRequire($dir . $f->getFilename() . "/") : require $dir . $f->getFilename();
        }
    }
}

/**
 * Ativa os logs do PHP
 *
 * @return void
 */
function debug()
{
    ini_set('display_errors', 1);
    ini_set('display_startup_erros', 1);
    error_reporting(E_ALL);
}

if (!function_exists('apache_request_headers')) {
    /**
     * Declara a função apache_request_headers caso não exista
     *
     * @return array
     */
    function apache_request_headers(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

/**
 * Trata os fatal errors que possam ocorrer no sistema
 * @return void json
 */
function catch_fatal_error(): void
{
    $lastError     = error_get_last();
    $captureErrors = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR]; // Códigos de erro para tratar
    /* Trata os erros que finalizam a execução do código */
    if ($lastError !== null && in_array($lastError['type'], $captureErrors)) {
        $file     = $lastError['file'];
        $fileLine = $lastError['line'];
        $message  = $lastError['message'];

        $file = explode(DIRECTORY_SEPARATOR, $file);
        $file = $file[count($file) - 2] . '/' . $file[count($file) - 1];
        $file = str_replace('.php', '', $file);

        if (strpos($message, 'Stack trace:') !== false) {
            $message = explode('Stack trace:', $message);
            $message = $message[0];
        }

        if (strpos($message, "\n") !== false) {
            $message = explode("\n", $message);
            $auxArray = [];
            foreach ($message as $msg) {
                if (!empty($msg)) {
                    $auxArray[] = str_replace(
                        '.php',
                        '',
                        str_replace(DIRECTORY_SEPARATOR, '/', str_replace(__DIR__, '', $msg))
                    );
                }
            }
            $message = count($auxArray) > 1 ? $auxArray : $auxArray[0];
        }

        App\Core\Handlers\Response\ResponseHandler::printJson('E000-000', 500, ["error" => [
            "arquivo"  => $file,
            "linha"    => $fileLine,
            "mensagem" => $message,
        ]]);
    }
}
