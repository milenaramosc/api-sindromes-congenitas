<?php

namespace CLI\Commands\Help;

use CLI\Utils\Message;
use CLI\Utils\Str;

/**
 * php pibic -h
 * php pibic -h=commandName
 * php pibic --help
 * php pibic --help=commandName
 */
class Help
{
    private array $commands;

    private array $helpSearch = [];

    public function __construct(array $opts)
    {
        $this->setHelpSearch($opts);
        $this->commands = $this->commands();
    }

    public function list(): void
    {
        if ($this->helpSearch === []) {
            Message::printLine("Lista de comandos\n");
            foreach ($this->commands as $commandName => $exemples) {
                $this->printExemples($exemples, $commandName);
            }

            return;
        }

        $commandsLike = [];
        foreach ($this->helpSearch as $value) {
            $addCommands = $this->getCommandsLike($value);
            foreach ($addCommands as $addCommand) {
                if (!in_array($addCommand, $commandsLike)) {
                    $commandsLike[] = $addCommand;
                }
            }
        }

        if ($commandsLike === []) {
            $this->showHelp();
        }

        Message::printLine("Comandos encontrados");
        foreach ($commandsLike as $commandName) {
            $this->printExemples($this->commands[$commandName], $commandName);
            Message::printLine();
        }
    }

    public function showHelp()
    {
        Message::end("Execute '{$this->commands['h'][0]}' para obter ajuda");
    }

    private function setHelpSearch(array $opts): void
    {
        if (isset($opts['h'])) {
            $this->addHelpSearch($opts['h']);
        }

        if (isset($opts['help'])) {
            $this->addHelpSearch($opts['help']);
        }
    }

    private function addHelpSearch($val): void
    {
        if ($val !== false) {
            $this->helpSearch = array_merge(
                $this->helpSearch,
                is_array($val) ? $val : [$val]
            );
        }
    }

    /**
     * Exibe os comandos
     *
     * @param array $exemples
     * @param string $commandName
     * @return void
     */
    private function printExemples(array $exemples, string $commandName): void
    {
        Message::printLine("\n$commandName");
        foreach ($exemples as $exemple) {
            Message::printLine("\t$exemple");
        }
    }

    /**
     * Busca comandos que se pareçam com a busca
     *
     * @param string $search
     * @return array
     */
    private function getCommandsLike(string $search): array
    {
        $likeKeys = [];
        foreach (array_keys($this->commands) as $commandName) {
            if (Str::has($commandName, $search)) {
                $likeKeys[] = $commandName;
            }
        }
        return $likeKeys;
    }

    /**
     * Retorna um array com os exemplos de comandos
     *
     * @return array
     */
    private function commands(): array
    {
        return [
            "h"                => $this->help(),
            "help"             => $this->help(),
            "make:model"       => $this->makeModel(),
            "make:module"      => $this->makeModule(),
            "make:message"     => $this->makeMessage(),
            "make:controller"  => $this->makeController(),
            "make:migration"   => $this->makeMigration(),
            "make:seeder"      => $this->makeSeeder(),
            "make:comprovante" => $this->makeComprovante(),
        ];
    }

    /**
     * Exemplos da chamada do comando 'h' e 'help'
     *
     * @return array
     */
    private function help(): array
    {
        return [
            "php pibic -h",
            "php pibic -h=comando",
            "php pibic --help",
            "php pibic --help=comando"
        ];
    }

    /**
     * Exemplos da chamada do comando 'make:model'
     *
     * @return array
     */
    private function makeModel(): array
    {
        return ["php pibic --make:model nomeModel"];
    }

    /**
     * Exemplos da chamada do comando 'make:module'
     *
     * @return array
     */
    private function makeModule(): array
    {
        return [
            "php pibic --make:module nomeDoModulo",
            "php pibic --make:module nomeDoModulo -p \"nomeDoProduto1, nomeDoProduto2, ...\"",
            "php pibic --make:module nomeDoModulo --products \"nomeDoProduto1, nomeDoProduto2, ...\""
        ];
    }

    /**
     * Exemplos da chamada do comando 'make:message'
     *
     * @return array
     */
    private function makeMessage(): array
    {
        return ["php pibic --make:message nomeDaPastaDeMensagens"];
    }

    /**
     * Exemplos da chamada do comando 'make:controller'
     *
     * @return array
     */
    private function makeController(): array
    {
        return ["php pibic --make:controller nomeDoController"];
    }

    /**
     * Exemplos da chamada do comando 'make:migration'
     *
     * @return array
     */
    private function makeMigration(): array
    {
        return ["php pibic --make:migration nomeDaMigration"];
    }

    /**
     * Exemplos da chamada do comando 'make:seeder'
     *
     * @return array
     */
    private function makeSeeder(): array
    {
        return ["php pibic --make:seeder nomeDaSeed"];
    }

    /**
     * Exemplos da chamada do comando 'make:comprovante'
     *
     * @return array
     */
    private function makeComprovante(): array
    {
        return ["php pibic --make:comprovante nomeDoComprovante"];
    }
}
