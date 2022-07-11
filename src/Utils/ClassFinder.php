<?php

namespace App\Utils;

class ClassFinder
{
    //This value should be the directory that contains composer.json
    /**
     * @var string
     */
    public const appRoot = __DIR__ . "/../../";


    private static function autoloadClasses()
    {
        spl_autoload_register(function ($class) {
            $pathClass = $class;
            $pathClass = str_replace('\\', DIRECTORY_SEPARATOR, $pathClass);
            $pathClass = str_replace('App', __DIR__ . '/..', $pathClass);

            $folders = explode(DIRECTORY_SEPARATOR, $pathClass);
            $file = end($folders);

            $pathFile = realpath($pathClass . DIRECTORY_SEPARATOR . $file . '.php');
            include_once $pathFile;
        });
    }

    public static function getClassesInNamespace($namespace)
    {
        $directory =  self::getNamespaceDirectory($namespace);
        $files = scandir($directory);

        $classes = array_map(function ($file) use ($namespace) {
            return $namespace . '\\' . str_replace('.php', '', $file);
        }, $files);

        return array_filter($classes, function ($possibleClass) {
            /* We need autoload classes that is in folders */
            self::autoloadClasses();
            return class_exists($possibleClass);
        });
    }

    private static function getDefinedNamespaces()
    {
        $composerJsonPath = self::appRoot . 'composer.json';
        $composerConfig = json_decode(file_get_contents($composerJsonPath));

        return (array) $composerConfig->autoload->{'psr-4'};
    }

    private static function getNamespaceDirectory($namespace)
    {
        $composerNamespaces = self::getDefinedNamespaces();

        $namespaceFragments = explode('\\', $namespace);
        $undefinedNamespaceFragments = [];

        while ($namespaceFragments) {
            $possibleNamespace = implode('\\', $namespaceFragments) . '\\';

            if (array_key_exists($possibleNamespace, $composerNamespaces)) {
                return realpath(self::appRoot . $composerNamespaces[$possibleNamespace] . implode('/', $undefinedNamespaceFragments));
            }

            array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));
        }

        return false;
    }
}
