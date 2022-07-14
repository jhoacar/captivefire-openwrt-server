<?php

namespace App\GraphQL\Queries;

use App\GraphQL\Loader;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Class used for the global queries in GraphQL.
 */
class QueryType extends ObjectType
{
    /*
     * This trait load all fields in each folder for this namespace
     */
    use Loader;

    /**
     * Global instance in all the aplication.
     * @var QueryType
     */
    private static $query;

    /**
     * Return the global instance for the queries in GraphQL.
     * @return QueryType
     */
    public static function query()
    {
        return self::$query ?: (self::$query = new self());
    }

    /**
     * We use a private construct method for prevent instances
     * Its called as singleton pattern.
     */
    private function __construct()
    {
        $this->namespace = __NAMESPACE__;
        $this->searchFields();
        $config = [
            'name' => 'Query',
            'fields' => $this->fields,
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                /**
                 * Execute this function load the root value for the fields
                 * If a method in this class has the name 'resolve' . $fieldName
                 * is called for resolve, empty string for the root value otherwise.
                 */
                $method = 'resolve' . ucfirst($info->fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($value, $args, $context, $info);
                } else {
                    return '';
                }
            },
        ];
        parent::__construct($config);
    }
}
