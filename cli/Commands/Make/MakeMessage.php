<?php

namespace CLI\Commands\Make;

use App\Model\CodigosMensageria;
use CLI\Utils\Dir;
use CLI\Utils\FileMaker;
use CLI\Utils\Math;
use CLI\Utils\Message;
use CLI\Utils\Str;

/**
 * php rbm --make:message messageName
 */
class MakeMessage extends Make
{
    /**
     * Nome do novo message
     *
     * @var string
     */
    private string $messageName;

    /**
     * Caminho para a pasta de mensagens no sistema
     *
     * Por padrão, app/message
     *
     * @var string
     */
    private string $messagesPath;

    /**
     * Caminho para a nova pasta de mensagens
     *
     * Por padrão, app/message/{$this->messageName}
     *
     * @var string
     */
    private string $messageFolderPath;

    /**
     * Range do código da mensagem
     *
     * @var string
     */
    private string $range;

    private CodigosMensageria $model;

    /**
     * Nome do comando
     *
     * @var string
     */
    private const COMMAND = "make:message";

    public function __construct(array $opts)
    {
        $this->model = new CodigosMensageria();

        parent::__construct($opts);
        $this->messagesPath = "app" . DIRECTORY_SEPARATOR . "messages";

        $this->setMessageRange();
    }

    protected function readArgs(): array
    {
        return ["messageName" => Str::camelCase($this->opts[self::COMMAND])];
    }

    public function execute(): void
    {
        try {
            $this->model->begin();

            $this->messageName = $this->args['messageName'];
            $this->messageFolderPath = $this->messagesPath . DIRECTORY_SEPARATOR . $this->messageName;

            Dir::create($this->messageFolderPath);
            $this->makeErrorFile();
            $this->makeSuccessFile();
            $this->saveCreatedCode();

            $this->model->commit();

            Message::printLine("\nMensagens criadas com sucesso!");
            Message::printLine("Pasta: {$this->messageFolderPath}");
            Message::printLine();
        } catch (\Throwable $th) {
            $this->model->rollBack();
            Message::endWithError("Ocorreu um erro ao criar as mensagens:\n$th");
        }
    }

    private function makeErrorFile()
    {
        $this->makeMessageFile("Error", "E", "Mensagem de erro!");
    }

    private function makeSuccessFile()
    {
        $this->makeMessageFile("Success", "S", "Mensagem de sucesso!");
    }

    private function makeMessageFile(string $fileName, string $type, string $message): void
    {
        $fileMaker = new FileMaker(
            $this->messageFolderPath
                . DIRECTORY_SEPARATOR
                . $fileName,
            "Message",
            "Message",
            [
                "{range}" => $type . $this->range,
                "{message}" => $message
            ]
        );

        $fileMaker->make();
    }

    private function setMessageRange(): void
    {
        $this->range = Math::decTohex(hexdec($this->model->getLastCode()) + hexdec("1"));
    }

    private function saveCreatedCode(): void
    {
        $this->model->saveCode($this->range, $this->messageName);
        Message::printLine("Código adicionado no banco de dados");
    }
}
