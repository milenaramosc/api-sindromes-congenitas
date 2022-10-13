<?php

namespace CLI;

class Commands
{
    public static function options(): array
    {
        return getopt(
            "h::p:",
            [
                "help::",
                "products:",
                "make:model:",
                "make:module:",
                "make:message:",
                "make:controller:",
                "make:migration:",
                "make:seeder:",
                "make:comprovante:"
            ]
        );
    }
}
