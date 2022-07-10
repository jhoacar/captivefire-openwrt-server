<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;

interface IQuery
{
    /**
     * This is a method to resolve the query
     */
    // public static function resolver($val, $args, $context, ResolveInfo $info): array;

    /**
     * This is a method to resolve the fields
     */
    public static function getFields(): array; 
}
