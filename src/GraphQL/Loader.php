<?php

namespace App\GraphQL;

use App\GraphQL\ILoader;
use App\Utils\ClassFinder;

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
     * This function load all classes using this namespace,
     * using invoke method for each one
     */
    private function searchFields(): void
    {
        $classes = ClassFinder::getClassesInNamespace(__NAMESPACE__);

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
