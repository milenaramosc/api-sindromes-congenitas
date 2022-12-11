<?php

namespace CLI\Commands\Make;

use CLI\Utils\FileMaker;
use CLI\Utils\Message;
use CLI\Utils\Str;

/**
 * php rbm --make:comprovante nomeDoComprovante
 */
class MakeComprovante extends Make
{
    /**
     * Nome do novo comprovante
     *
     * @var string
     */
    private string $comprovanteName;

    /**
     * Caminho para a pasta dos comprovantes no sistema
     *
     * Por padrão, app\modules\Comprovantes
     *
     * @var string
     */
    private string $path;

    /**
     * Nome do comando
     *
     * @var string
     */
    private const COMMAND = "make:comprovante";

    /**
     * Namespace dos comprovantes
     *
     * @var string
     */
    private const NAMESPACE = "Modules\\Comprovantes";

    public function __construct(array $opts)
    {
        parent::__construct($opts);
        $this->path = "app" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "Comprovantes";
    }

    protected function readArgs(): array
    {
        return ["comprovanteName" => Str::pascalCase($this->opts[self::COMMAND])];
    }

    public function execute(): void
    {
        $this->comprovanteName = Str::addToStartNotHaving($this->args['comprovanteName'], "Comprovante");
        $this->makeFile();
        Message::printLine("Comprovante '{$this->comprovanteName}' criado com sucesso");
        Message::printLine("Lembre-se de mapear o novo comprovante em app\\controller\\ComprovanteController.php");
    }

    private function makeFile()
    {
        $fileMaker = new FileMaker(
            $this->path
                . DIRECTORY_SEPARATOR
                . $this->comprovanteName,
            "Comprovante",
            "Comprovante",
            [
                "{namespace}" => self::NAMESPACE,
                "{comprovanteName}" => $this->comprovanteName,
            ]
        );

        $fileMaker->make();
    }
}
