<?php

namespace App\GraphQL;

use App\GraphQL\Mutations\MutationType;
use App\GraphQL\Queries\QueryType;
use GraphQL\Type\Schema as BaseSchema;

/**
 * Class used for the global schema in GraphQL.
 */
class Schema extends BaseSchema
{
    /**
     * Global instance in all the aplication.
     * @var Schema
     */
    private static $instance;

    /*
     * We use a private construct method for prevent instances
     * Its called as singleton pattern
    */
    private function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * Return the global instance for the schema in GraphQL.
     * @return Schema
     */
    public static function get(): self
    {
        return self::$instance ?: (self::$instance = new self([
            'query' => QueryType::query(),
            'mutation' => MutationType::mutation(),
        ]));
    }
}
