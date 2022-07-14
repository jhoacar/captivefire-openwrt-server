<?php

namespace App\Utils;

use stdClass;

/**
 * Class used for execute uci commands in the system.
 */
class UciCommand extends Command
{
    /**
     * This is the text that shows the uci system when a resource is not found.
     * @var string
     */
    public const NOT_FOUND = 'not found';

    /**
     * Return a string to use in a command shell.
     * @param string
     * @return string
     */
    private static function cleanInput($input): string
    {
        return escapeshellcmd(escapeshellarg($input));
    }

    /**
     * Return the output for the specified resource
     * Validate the each field used as input
     * Also if the resource is not found return an empty string.
     * @param string $config file to find in /etc/config for default
     * @param string $section to find in the config
     * @param string $option to find in the section for the config
     * @return string
     */
    public static function get(string $config, string $section, string $option): string
    {
        $config = self::cleanInput($config);
        $section = self::cleanInput($section);
        $option = self::cleanInput($option);
        $result = parent::execute("uci get $config.$section.$option");

        return str_contains($result, self::NOT_FOUND) ? '' : $result;
    }

    /**
     * Return an index in the string contained between [] or -1 otherwise
     * For example:
     *         For the input => '@system[14]'
     *         You obtain => 14.
     *
     *         For the input => 'system20'
     *         You obtain => -1
     * @param string name section
     * @return int
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
     * Return the name section that is contained between @ and [ or same string otherwise
     * For example:
     *          For the input => '@system[14]'
     *          You obtain => 'system'.
     *
     *          For the input => 'system20'
     *          You obtain => 'system20'
     * @param string name section
     * @return string
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
     * Return an object with the representation for the UCI System.
     *
     * For example:
     *  {
     *      app:{
     *          port
     *      }
     *  }
     *
     * @param void
     * @return array
     */
    public static function getUciConfiguration(): array
    {
        $CONFIGURATION = 0;
        $SECTION = 1;
        $OPTIONS = 2;

        $uciConfig = [];
        $configurations = explode(PHP_EOL, parent::execute('uci show'));

        foreach ($configurations as $info) {
            [$info, $content] = explode('=', $info);
            $information = explode('.', $info);

            if (!strlen($info) || count($information) < 3) {
                continue;
            }

            $config = $information[$CONFIGURATION];
            $section = $information[$SECTION];
            $option = explode('=', $information[$OPTIONS])[0];
            $content = explode(' ', $content);

            if (empty($uciConfig[$config])) {
                $uciConfig[$config] = [];
            }

            $sectionName = self::getNameSection($section);

            self::getUciSection($uciConfig[$config][$sectionName], $section, $option, $content);
        }

        return $uciConfig;
    }

    /**
     * - If a section is an array is saved as a Array.
     *
     *      - This array is saved with the position described by the uci system
     *
     * - If a section is not an array, so it's saved as a stdClass
     *
     *      - This stdClass has an attribute 'options' for each option in this section
     *
     * @param array &$configSection
     * @param string $sectionName
     * @param string $optionName
     */
    private static function getUciSection(&$configSection, $sectionName, $optionName, $content)
    {
        $isArraySection = str_contains($sectionName, '@');
        $indexArraySection = $isArraySection ? self::getIndexSection($sectionName) : -1;

        if ($isArraySection) {
            if (empty($configSection)) {
                $configSection = [];
            }

            if (empty($configSection[$indexArraySection])) {
                $configSection[$indexArraySection] = [];
            }
            $configSection[$indexArraySection][$optionName] = $content;
        } else {
            if (empty($configSection)) {
                $configSection = new stdClass();
            }

            if (empty($configSection->options)) {
                $configSection->options = [];
            }

            $configSection->options[$optionName] = $content;
        }
    }
}
