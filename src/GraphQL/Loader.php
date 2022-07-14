<?php

namespace App\GraphQL;

use App\Utils\ClassFinder;

/**
 * Contract to load all the fields in GraphQL.
 */
interface ILoader
{
    /**
     * Returns all fields for GraphQL for each implementation.
     * @return array
     */
    public static function getFields(): array;
}

/**
 * Trait (class) used by dependency injection
 * to search for classes found in the namespace.
 */
trait Loader
{
    /**
     * @var string
     */
    private $method = 'getFields';
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
     * Call each one using specific method
     * Load $fields and $classes attributes with his information.
     * @param void
     * @return void
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
