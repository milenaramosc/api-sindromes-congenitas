<?php

namespace CLI\Commands\Make;

abstract class Make
{
    /**
     * Opções recebidas do comando
     *
     * @var array
     */
    protected array $opts;

    protected array $args;

    /**
     * @param array $opts
     */
    public function __construct(array $opts)
    {
        $this->opts = $opts;
        $this->args = $this->readArgs();
    }

    abstract protected function readArgs(): array;

    /**
     * Executa o comando solicitado
     *
     * @return void
     */
    abstract public function execute(): void;
}
