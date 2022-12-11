<?php

namespace CLI\Commands\Make;

use Exception;

class Maker
{
    private const CLASS_MAP = [
        "make:model"       => "MakeModel",
        "make:module"      => "MakeModule",
        "make:message"     => "MakeMessage",
        "make:controller"  => "MakeController",
        "make:migration"   => "MakeMigration",
        "make:seeder"      => "MakeSeeder",
        "make:comprovante" => "MakeComprovante",
    ];

    public static function get(array $opts): Make
    {
        $command = "";
        foreach ($opts as $key => $value) {
            if (in_array($key, array_keys(self::CLASS_MAP))) {
                $command = $key;
            }
        }

        if ($command === "") {
            throw new Exception("Comando inválido");
        }

        $opts = $opts;

        $class = @self::CLASS_MAP[$command];
        if ($class === null) {
            throw new Exception("Comando '{$command}' não encontrado");
        }

        $class = "CLI\\Commands\\Make\\$class";

        return new $class($opts);
    }
}
