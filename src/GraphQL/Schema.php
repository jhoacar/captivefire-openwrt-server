<?php

namespace App\GraphQL;

use App\GraphQL\Mutations\MutationType;
use App\GraphQL\Queries\QueryType;
use GraphQL\Type\Schema as BaseSchema;

class Schema extends BaseSchema
{
    /**
     * @var Schema
     */
    private static $instance;

    /*************** Singleton Pattern **************/
    private function __construct($config)
    {
        parent::__construct($config);
    }

    /**
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
