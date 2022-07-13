<?php

namespace App\Utils;

use stdClass;

class UciCommand extends Command
{
    public static function get(string $config, string $section, string $option): string
    {
        $config = escapeshellcmd(escapeshellarg($config));
        $section = escapeshellcmd(escapeshellarg($section));
        $option = escapeshellcmd(escapeshellarg($option));
        $result = parent::execute("uci get $config.$section.$option");
        return !strlen($result) || str_contains($result, 'not found') ? "" : $result;
    }

    /**
     * @return int index in the string between [] or -1
     */
    private static function getIndexSection(string $section): int
    {
        $matches = [];
        $isFound = preg_match('(\[([0-9]*)\])', $section, $matches);
        if ($isFound) {
            return intval($matches[1]);
        }
        return -1;
    }
    /**
     * @return string string contained between @ and [
     */
    private static function getNameSection(string $section): string
    {
        $matches = [];
        $isFound = preg_match('(@([\s\S]*)\[)', $section, $matches);
        if ($isFound) {
            return $matches[1];
        }
        return $section;
    }

    /**
     * @return array uciConfig
     */
    public static function getUciConfiguration(): array
    {
        $CONFIGURATION = 0;
        $SECTION = 1;
        $OPTIONS = 2;

        $uciConfig = [];
        $configurations = explode(PHP_EOL, parent::execute('uci show'));
        foreach ($configurations as $info) {
            $information = explode('.', $info);

            if (!strlen($info) || count($information) < 3) {
                continue;
            }

            $config = $information[$CONFIGURATION];
            $section = $information[$SECTION];
            $option = explode('=', $information[$OPTIONS])[0];

            if (empty($uciConfig[$config])) {
                $uciConfig[$config] = [];
            }

            // If section is an Array
            $isArraySection = str_contains($section, '@');
            $indexArraySection = $isArraySection ? self::getIndexSection($section) : -1;
            $section = self::getNameSection($section);

            if ($isArraySection) {
                if (empty($uciConfig[$config][$section])) {
                    $uciConfig[$config][$section] = [];
                }

                if (empty($uciConfig[$config][$section][$indexArraySection])) {
                    $uciConfig[$config][$section][$indexArraySection] = [];
                }

                array_push($uciConfig[$config][$section][$indexArraySection], $option);
            } else {
                if (empty($uciConfig[$config][$section])) {
                    $uciConfig[$config][$section] = new stdClass();
                }

                if (empty($uciConfig[$config][$section]->options)) {
                    $uciConfig[$config][$section]->options = [];
                }

                array_push($uciConfig[$config][$section]->options, $option);
            }
        }

        return $uciConfig;
    }
}
