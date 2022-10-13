<?php

namespace CLI;

use CLI\Commands\Help\Help;
use CLI\Commands\Make\Maker;
use CLI\Utils\Message;
use CLI\Utils\Str;

class Runner
{
    public static function run(): void
    {
        // : a opção precisa ter um valor
        // :: a opção tem valor opcional
        $opts = Commands::options();

        Message::printLine("#----------------------RBM Conta Digital CLI----------------------#\n");

        $command = array_keys($opts)[0] ?? "";

        if (Str::has($command, "make")) {
            Maker::get($opts)->execute();
        }

        if ((Str::has($command, "help") || Str::has($command, "h")) || $opts === []) {
            $help = new Help($opts);
            $help->list();
        }

        Message::end();
    }
}
