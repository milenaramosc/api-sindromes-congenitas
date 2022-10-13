<?php

namespace CLI\Utils;

class Message
{
    public const INFO    = "info";
    public const ERROR   = "error";
    public const SUCCESS = "success";
    public const WARNING = "warning";
    public const DEFAULT = "default";

    public static function printLine(string $message = "", string $type = self::DEFAULT)
    {
        self::$type($message);
    }

    public static function end(string $message = "")
    {
        exit("$message\n\n#-------------------------------FIM-------------------------------#\n");
    }

    public static function endWithError(string $message)
    {
        self::error($message);
        self::end();
    }

    public static function info(string $message): void
    {
        echo "\033[36m$message \033[0m\n";
    }

    public static function error(string $message): void
    {
        echo "\033[31m$message \033[0m\n";
    }

    public static function success(string $message): void
    {
        echo "\033[32m$message \033[0m\n";
    }

    public static function warning(string $message): void
    {
        echo "\033[33m$message \033[0m\n";
    }

    public static function default(string $message): void
    {
        echo $message . "\n";
    }
}
