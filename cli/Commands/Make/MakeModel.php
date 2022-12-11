<?php

namespace CLI\Commands\Make;

use CLI\Utils\FileMaker;
use CLI\Utils\Message;
use CLI\Utils\Str;

/**
 * php rbm --make:model modelName
 */
class MakeModel extends Make
{
    /**
     * Nome do novo model
     *
     * @var string
     */
    private string $modelName;

    /**
     * Caminho para a pasta das models no sistema
     *
     * Por padrão, app/model
     *
     * @var string
     */
    private string $modelsPath;

    /**
     * Nome do comando
     *
     * @var string
     */
    private const COMMAND = "make:model";

    /**
     * Namespace das models
     *
     * @var string
     */
    private const MODEL_NAMESPACE = "App\\Model";

    public function __construct(array $opts)
    {
        parent::__construct($opts);
        $this->modelsPath = "app" . DIRECTORY_SEPARATOR . "model";
    }

    protected function readArgs(): array
    {
        return ["modelName" => Str::pascalCase($this->opts[self::COMMAND])];
    }

    public function execute(): void
    {
        $this->modelName = $this->args['modelName'];
        $this->makeModelFile();
        Message::printLine("Model criada com sucesso");
    }

    private function makeModelFile()
    {
        $fileMaker = new FileMaker(
            $this->modelsPath
                . DIRECTORY_SEPARATOR
                . $this->modelName,
            "Model",
            "Model",
            [
                "{namespace}" => self::MODEL_NAMESPACE,
                "{modelName}" => $this->modelName,
            ]
        );

        $fileMaker->make();
    }
}
