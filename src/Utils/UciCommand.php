<?php

namespace App\Utils;

class UciCommand extends Command
{
    public static function get(string $config, string $section, string $option): string
    {
        $config = escapeshellcmd(escapeshellarg($config));
        $section = escapeshellcmd(escapeshellarg($section));
        $option = escapeshellcmd(escapeshellarg($option));
        return parent::execute("uci get $config.$section.$option");
    }
}
