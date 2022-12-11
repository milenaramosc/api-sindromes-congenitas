<?php

namespace CLI\Commands\Make;

use CLI\Utils\FileMaker;
use CLI\Utils\LocalUserName;
use CLI\Utils\Message;
use CLI\Utils\Str;
use DateTimeImmutable;

/**
 * php rbm --make:migration migrationName
 */
class MakeMigration extends Make
{
    /**
     * Nome da migration
     *
     * @var string
     */
    private string $migrationName;

    /**
     * Caminho para a pasta das migrations
     *
     * Por padrão, app/database/Migrations
     *
     * @var string
     */
    private string $path;

    /**
     * Nome do arquivo de migration
     *
     * @var string
     */
    private string $fileName;

    /**
     * Data da criação da migration
     *
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $date;

    /**
     * Nome do comando
     *
     * @var string
     */
    private const COMMAND = "make:migration";

    public function __construct(array $opts)
    {
        parent::__construct($opts);
        $this->date = new DateTimeImmutable();
        $this->path =
            "database"
            . DIRECTORY_SEPARATOR
            . "Migrations";
    }

    protected function readArgs(): array
    {
        return ["migrationName" => Str::pascalCase($this->opts[self::COMMAND])];
    }

    public function execute(): void
    {
        $this->makeMigrationFile();
        Message::success("Migration criada com sucesso");
    }

    private function setFileName(string $migrationName)
    {
        $this->fileName = Str::snakeCase($this->date->getTimestamp() . $migrationName);
        $this->migrationName = $migrationName;
    }

    private function makeMigrationFile()
    {
        $this->setFileName($this->args['migrationName']);

        $fileMaker = new FileMaker(
            $this->path
                . DIRECTORY_SEPARATOR
                . $this->fileName,
            "Migration",
            "Migration",
            [
                "{migrationName}" => $this->migrationName,
                "{fileName}"      => $this->fileName,
                "{by}"            => LocalUserName::getWithPrefix(),
                "{date}"          => $this->date->format('Y-m-d H:i:s')
            ],
            "sql"
        );

        $fileMaker->make();
    }
}
