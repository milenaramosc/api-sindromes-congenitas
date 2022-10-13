<?php

namespace App\Core\Utils;

use App\Model\FilesLog;

class Files
{
    /**
     * Escreve em um arquivo
     * @param string $fileName
     * @param string $mode r|r+|w|w+|a|a+|x|x+
     * @param string $text
     * @return void
     */
    public static function write(
        $fileName,
        $mode,
        $text
    ) {
        $file = fopen($fileName, $mode);

        if (!$file) {
            return false;
        }

        fwrite($file, $text);
        fclose($file);
    }

    /**
     * @name download
     * @param string $cpfcnpj
     * @param string $fileLink
     * @param string $fileName
     * @param string $filePath
     * @param array $trustedUrls
     * @param string $extention
     * @return array|false
     */
    public function download(
        $cpfcnpj,
        $fileLink,
        $filePath = "",
        $fileName = "",
        $extention = "",
        $trustedUrls = array(),
        $codTerceiros = 0
    ) {
        // $url = parse_url($fileLink, PHP_URL_HOST);
        // print_r($trustedUrls);
        // if (!empty($trustedUrls) && !in_array($url, $trustedUrls)) return false;
        // print_r($url);die;

        self::createDir($filePath);

        $fileContent = file_get_contents($fileLink);
        if ($fileContent === false) {
            return false;
        }

        $fileName     = empty($fileName) ? '/' . $cpfcnpj . time() . rand(100, 999) : '/' . $fileName;
        $fullFilePath = $filePath . $fileName . $extention;
        $stored       = file_put_contents($fullFilePath, $fileContent);
        if ($stored === false) {
            return false;
        }

        $filesModel = new FilesLog();
        if (!$filesModel->insert($cpfcnpj, $fileLink, $fullFilePath, $codTerceiros)) {
            return false;
        }

        return array(
            "file" => array(
                "path" => $filePath,
                "size" => $stored
            )
        );
    }

    /**
     * Cria um diretório
     * @param string $dirName
     * @param int $permissions
     * @return void
     */
    public static function createDir($dirName, $permissions = 0775)
    {
        if (!is_dir($dirName)) {
            mkdir($dirName, $permissions);
        }
    }

    public static function saveUploadImg(string $dir, string $fileName, string $b64Decoded): bool
    {
        ini_set('max_execution_time', '600');
        $dir = ABSOLUTE_IMG_DIR . DIRECTORY_SEPARATOR . $dir;
        self::createDir($dir);
        return file_put_contents($dir . DIRECTORY_SEPARATOR . $fileName, $b64Decoded) !== false;
    }

    public static function deleteImg(string $dir, string $fileName): bool
    {
        if (self::checkImgExists($dir, $fileName)) {
            return unlink(ABSOLUTE_IMG_DIR . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $fileName);
        }
        return false;
    }

    public static function checkImgExists($dir, $fileName): bool
    {
        return file_exists(ABSOLUTE_IMG_DIR . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $fileName);
    }

    /**
     * @name logThirdpartyRequests
     * @param string $by COD_TERCEIROS da tabela auth_terceiros
     * @param string $by fileName
     * @param string $responseStatusCode Status code retornado pela API
     * @param string $outputJsonData Json retornado pela api
     * @return void
     */
    public static function logThirdpartyRequests($by, $fileName, $responseStatusCode, $outputJsonData)
    {
        $dirName = "logs/" . date('Y-m-d');
        self::createDir($dirName);
        $inputJsonData = $GLOBALS['originalJson'];
        if (is_object($inputJsonData) || is_array($inputJsonData)) {
            $inputJsonData = json_encode($inputJsonData);
        } else {
            $inputJsonData = json_decode(trim(preg_replace("/\r|\n/", "", $inputJsonData)));
            $inputJsonData = json_encode($inputJsonData);
        }

        self::write(
            $dirName . "/" . $fileName,
            'a',
            $by
                . "\t"
                . date('Y-m-d H:i:s')
                . "\t"
                . $responseStatusCode
                . "\t"
                . $inputJsonData
                . "\t"
                . $outputJsonData
                . "\n"
        );
    }

    /**
     * @name createThirdpartyLogsTransacaoCredito
     * @param string $by COD_TERCEIROS da tabela auth_terceiros
     * @param string $responseStatusCode Status code retornado pela API
     * @param string $inputJsonData Json recebido pela api
     * @param string $outputJsonData Json retornado pela api
     * @return void
     */
    public static function createThirdpartyLogsTransacaoCredito($by, $responseStatusCode, $outputJsonData)
    {
        $dirName = "logs/" . date('Y-m-d');
        self::createDir($dirName);
        $inputJsonData = $GLOBALS['originalJson'];
        if (is_object($inputJsonData) || is_array($inputJsonData)) {
            $inputJsonData = json_encode($inputJsonData);
        } else {
            $inputJsonData = json_decode(trim(preg_replace("/\r|\n/", "", $inputJsonData)));
            $inputJsonData = json_encode($inputJsonData);
        }

        self::write(
            $dirName . "/logs_transacoes_credito_terceiros.log",
            'a',
            $by
                . "\t"
                . date('Y-m-d H:i:s')
                . "\t"
                . $responseStatusCode
                . "\t"
                . $inputJsonData
                . "\t"
                . $outputJsonData
                . "\n"
        );
    }

    public static function fileExtension($imageData): string
    {
        $imageTypes = [
            "jpeg" => "FFD8",
            "png" => "89504E470D0A1A0A",
            "gif" => "474946",
            "bmp" => "424D",
            "tiff" => "4949",
            "tiff" => "4D4D"
        ];

        foreach ($imageTypes as $mime => $hexBytes) {
            $bytes = Str::getBytesFromHexString($hexBytes);
            if (substr($imageData, 0, strlen($bytes)) == $bytes) {
                return $mime;
            }
        }

        return '';
    }

    public static function moveAccountDocuments(string $cpfCnpj, string $conta, string $fileName): bool
    {
        $from = ABSOLUTE_IMG_DIR . DIRECTORY_SEPARATOR . $cpfCnpj;
        $to = $from . DIRECTORY_SEPARATOR . $conta;

        self::createDir($to);

        return rename(
            $from . DIRECTORY_SEPARATOR . $fileName,
            $to . DIRECTORY_SEPARATOR . $fileName
        );
    }

    /**
     * Lê o conteúdo de um arquivo
     *
     * @param string $filePath
     *
     * @return string
     *
     * @throws \Throwable
     */
    public static function readFile(string $filePath): string
    {
        $content = file_get_contents($filePath);

        if ($content === false) {
            throw new \DomainException("Arquivo '$filePath' não pode ser lido");
        }

        return $content;
    }
}
