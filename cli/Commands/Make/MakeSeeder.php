<?php

namespace CLI\Commands\Make;

use CLI\Utils\FileMaker;
use CLI\Utils\LocalUserName;
use CLI\Utils\Message;
use CLI\Utils\Str;
use DateTimeImmutable;

/**
 * php rbm --make:seeder seederName
 */
class MakeSeeder extends Make
{
    /**
     * Nome da seeder
     *
     * @var string
     */
    private string $seederName;

    /**
     * Caminho para a pasta de seeders
     *
     * Por padrão, app/database/Seeders
     *
     * @var string
     */
    private string $path;

    /**
     * Nome do arquivo de seeder
     *
     * @var string
     */
    private string $fileName;

    /**
     * Data da criação do seeder
     *
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $date;

    /**
     * Nome do comando
     *
     * @var string
     */
    private const COMMAND = "make:seeder";

    public function __construct(array $opts)
    {
        parent::__construct($opts);
        $this->date = new DateTimeImmutable();
        $this->path =
            "database"
            . DIRECTORY_SEPARATOR
            . "Seeders";
    }

    protected function readArgs(): array
    {
        return ["seederName" => Str::pascalCase($this->opts[self::COMMAND])];
    }

    public function execute(): void
    {
        $this->makeSeederFile();
        Message::success("Seeder criado com sucesso");
    }

    private function setFileName(string $fileName)
    {
        $this->fileName = Str::snakeCase($this->date->getTimestamp() . $fileName);
        $this->seederName = $fileName;
    }

    private function makeSeederFile()
    {
        $this->setFileName($this->args['seederName']);

        $fileMaker = new FileMaker(
            $this->path
                . DIRECTORY_SEPARATOR
                . $this->fileName,
            "Migration",
            "Seeder",
            [
                "{seederName}" => $this->seederName,
                "{fileName}"   => $this->fileName,
                "{by}"         => LocalUserName::getWithPrefix(),
                "{date}"       => $this->date->format('Y-m-d H:i:s')
            ],
            "sql"
        );

        $fileMaker->make();
    }
}
