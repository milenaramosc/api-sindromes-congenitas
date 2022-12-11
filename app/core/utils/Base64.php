<?php

namespace App\Core\Utils;

class Base64
{
    public function urlEncode(string $str)
    {
        return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
    }

    public function urlDecode(string $str)
    {
        return base64_decode(strtr($str, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($str)) % 4));
    }

    /**
     * Base64 encode imagem
     *
     * @param string $filePath
     *
     * @return string
     */
    public static function encodeImage(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new \DomainException("Imagem não encontrada");
        }

        return 'data:image/'
            . pathinfo($filePath, PATHINFO_EXTENSION)
            . ';base64,'
            . base64_encode(file_get_contents($filePath));
    }
}
