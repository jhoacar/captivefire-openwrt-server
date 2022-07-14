<?php

namespace App\Utils;

/**
 * Class used for find classes in namespace
 */
class ClassFinder
{
    /**
     * This value should be the directory that contains composer.json
     * @var string
     */
    public const APP_ROOT = __DIR__ . '/../../';

    /**
     * This method is an autoloader if a class is not founded with psr-4 standard
     * @param void
     * @return void
     */
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

    /**
     * Search all classes defined in the namespace using autoloading
     * based in psr-4 standard, only serach in the directory and a level
     * for subdirectories.
     * @param string
     * @return array
     */
    public static function getClassesInNamespace(string $namespace): array
    {
        $directory = self::getNamespaceDirectory($namespace);
        $files = scandir($directory);
        $classes = [];

        foreach ($files as $file) {
            $className = $namespace . '\\' . str_replace('.php', '', $file);
            if (str_contains($file, '.php')) {
                array_push($classes, $className);
            } elseif ($file !== '.' && $file !== '..') {
                $subdirectory = self::getNamespaceDirectory($className);
                $subfiles = scandir($subdirectory);

                foreach ($subfiles as $subfile) {
                    $subClassName = $className . '\\' . str_replace('.php', '', $subfile);
                    if (str_contains($subfile, '.php')) {
                        array_push($classes, $subClassName);
                    }
                }
            }
        }

        return array_filter($classes, function ($possibleClass) {
            return class_exists($possibleClass);
        });
    }

    /**
     * Return the standard autoload psr-4 definition in composer.json
     * @param void
     * @return array
     */
    private static function getDefinedNamespaces() : array
    {
        $composerJsonPath = self::APP_ROOT . 'composer.json';
        $composerConfig = json_decode(file_get_contents($composerJsonPath));

        return (array) $composerConfig->autoload->{'psr-4'};
    }

    /**
     * Returns the namespace directory if it exists or false otherwise
     * @param string
     * @return string|bool
     */
    private static function getNamespaceDirectory($namespace)
    {
        $composerNamespaces = self::getDefinedNamespaces();

        $namespaceFragments = explode('\\', $namespace);
        $undefinedNamespaceFragments = [];

        while ($namespaceFragments) {
            $possibleNamespace = implode('\\', $namespaceFragments) . '\\';

            if (array_key_exists($possibleNamespace, $composerNamespaces)) {
                return realpath(self::APP_ROOT . $composerNamespaces[$possibleNamespace] . implode('/', $undefinedNamespaceFragments));
            }

            array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));
        }

        return false;
    }
}
