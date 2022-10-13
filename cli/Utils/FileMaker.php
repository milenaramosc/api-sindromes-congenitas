<?php

namespace CLI\Utils;

class FileMaker
{
    /**
     * Caminho completo para o novo arquivo
     *
     * @example "pasta1/pasta2/arquivo.php"
     *
     * @var string
     */
    private string $newFilePath;

    /**
     * Nome do arquivo template
     *
     * @var string
     */
    private string $templateFileName;

    /**
     * Nome da pasta de template
     *
     * @var string
     */
    private string $templateFolderPath;

    /**
     * Parâmetros para substituição no template
     *
     * @example [
     *  "{param1}" => "Parâmetro 1",
     *  "{param2}" => "Parâmetro 2",
     * ]
     *
     * @var array
     */
    private array $templateParameters;

    public function __construct(
        string $newFilePath,
        string $templateType,
        string $templateFileName,
        array $templateParameters = [],
        $newFileExtention = "php"
    ) {
        $this->newFilePath = "$newFilePath.$newFileExtention";
        $this->templateType = $templateType;
        $this->templateFileName = "$templateFileName.tp.$newFileExtention";
        $this->templateParameters = $templateParameters;
        $this->templateFolderPath =
            ABSOLUTE_MAIN_DIR
            . DIRECTORY_SEPARATOR
            . "cli"
            . DIRECTORY_SEPARATOR
            . "Templates"
            . DIRECTORY_SEPARATOR
            . $templateType
            . DIRECTORY_SEPARATOR;
    }

    /**
     * Cria um arquivo com base em um template
     *
     * @return void
     */
    public function make(): void
    {
        $content = file_get_contents($this->templateFolderPath . $this->templateFileName);

        if ($content === false) {
            Message::endWithError("Erro ao ler o arquivo template");
        }

        $content = str_replace('<@php', '<?php', $content);
        foreach ($this->templateParameters as $key => $value) {
            $content = str_replace($key, $value, $content);
        }

        $this->createFile($content);
    }

    private function exists(): bool
    {
        return file_exists($this->newFilePath);
    }

    /**
     * Escreve um arquivo
     *
     * @param string $content
     * @return void
     */
    private function createFile(string $content): void
    {
        if ($this->exists()) {
            Message::endWithError("Arquivo {$this->newFilePath} já existe");
        }

        $file = fopen($this->newFilePath, 'w');

        if ($file === false) {
            Message::endWithError("Ocorreu um erro ao criar o arquivo {$this->newFilePath}");
        }

        if (fwrite($file, $content) === null) {
            Message::endWithError("Ocorreu um erro ao escrever o arquivo {$this->newFilePath}");
        }

        if (fclose($file) === false) {
            Message::endWithError("Ocorreu um erro ao fechar o arquivo {$this->newFilePath}");
        }

        Message::info("{$this->newFilePath} criado");
    }
}
