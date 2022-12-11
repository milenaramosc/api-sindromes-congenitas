<?php

namespace CLI\Utils;

class Dir
{
    public static function create(string $dirName)
    {
        if (file_exists($dirName)) {
            Message::end("Diretório $dirName já existe.");
        }

        if (!mkdir($dirName, 0775, true)) {
            Message::end("Não foi possível criar o diretório '$dirName'");
        }
    }
}
