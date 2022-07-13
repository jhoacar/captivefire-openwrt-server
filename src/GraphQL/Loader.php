<?php

namespace App\GraphQL;

use App\Utils\ClassFinder;

interface ILoader
{
    /**
     * This is a method to resolve the fields
     */
    public static function getFields(): array;
}

trait Loader
{
    /**
     * @var string
     */
    private $method = "getFields";
    /**
     * @var string
     */
    private $interface = ILoader::class;
    /**
     * @var array
     */
    private $fields = [];
    /**
     * @var array
     */
    private $classes = [];
    /**
     * @var string
     */
    protected $namespace = __NAMESPACE__;

    /**
     * This function load all classes using this namespace,
     * calling a specified static method
     */
    private function searchFields(): void
    {
        $classes = ClassFinder::getClassesInNamespace($this->namespace);

        foreach ($classes as $class) {
            if (
                in_array($this->interface, class_implements($class), true) &&
                method_exists($class, $this->method)
            ) {
                $result = call_user_func([$class, $this->method]); // ($class)();

                foreach ($result as $key => $value) {
                    $this->fields[$key] = $value;
                    $this->classes[$key] = $class;
                }
            }
        }
    }
}
