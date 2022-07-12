<?php

namespace App\GraphQL\Queries;

use App\GraphQL\Loader;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class QueryType extends ObjectType
{
    /**
     * This trait load all fields in each folder for this namespace
     */
    use Loader;

    /**
     * @var QueryType
     */
    private static $query;


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
}
