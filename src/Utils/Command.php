<?php

namespace App\Utils;

class Command
{
    public const NOT_FOUND = "not found";

    public static function execute(string $command): string
    {
        // Remove all break lines and redirect stderr to stdout
        return preg_replace('/(\r\n|\n|\r)/m', '', shell_exec("$command 2>&1"));
    }
}
