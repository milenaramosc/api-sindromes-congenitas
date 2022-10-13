<?php

namespace App\Core\Utils;

class SslKeys
{
    private string $dir;

    public function __construct(string $dir)
    {
        $this->setDir($dir);
    }

    /**
     * Retorna um resource de chave pública válida
     *
     * @return resource
     * @throws DomainException
     */
    public function getPublic()
    {
        $key = openssl_pkey_get_public($this->read("public"));

        if ($key === false) {
            throw new \DomainException("Chave pública inválida", 1);
        }

        return $key;
    }

    /**
     * Retorna um resource de chave privada válida
     *
     * @return resource
     * @throws DomainException
     */
    public function getPrivate()
    {
        $key = openssl_pkey_get_private($this->read("private"));

        if ($key === false) {
            throw new \DomainException("Chave privada inválida", 1);
        }

        return $key;
    }

    /**
     * Seta o caminho absoluto até a pasta que contém os arquivos de chaves SSL
     *
     * @param string $dir
     * @return void
     */
    private function setDir(string $dir)
    {
        $this->dir = ABSOLUTE_KEYS_DIR
            . DIRECTORY_SEPARATOR
            . $dir
            . DIRECTORY_SEPARATOR;
    }

    /**
     * Lê um arquivo de chave SSL
     *
     * @param string $fileName
     * @return string
     * @throws DomainException
     */
    private function read(string $fileName): string
    {
        $fileName = "{$this->dir}$fileName.pem";

        $content = file_get_contents("$fileName");

        if ($content === false) {
            throw new \DomainException("Não foi possível ler o conteúdo do arquivo '$fileName'", 1);
        }

        return $content;
    }
}
