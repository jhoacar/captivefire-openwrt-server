<?php

namespace App\GraphQL\Queries;

use App\Utils\ClassFinder;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class QueryType extends ObjectType
{
    /**
     * @var QueryType
     */
    private static $query;
    /**
     * @var string
     */
    private $method = "getFields";
    /**
     * @var string
     */
    private $interface = IQuery::class;
    /**
     * @var array
     */
    private $fields = [];
    /**
     * @var array
     */
    private $classes = [];

    /**
     * Singleton Pattern
     * @return QueryType
     */
    public static function query()
    {
        return self::$query ?: (self::$query = new QueryType());
    }

    /*************** Singleton Pattern **************/
    private function __construct()
    {
        $this->searchFields();
        $config = [
            'name' => 'Query',
            'fields' => $this->fields,
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                /* Execute this function load the root value for the fields */
                $method = $info->fieldName;
                if (method_exists($this, $method)) {
                    return $this->{$method}($value, $args, $context, $info);
                } else {
                    return "";
                }
            }
        ];
        parent::__construct($config);
    }

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
