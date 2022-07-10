<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class MutationType extends ObjectType
{
    private static $mutation;

    /**
     * Singleton Pattern
     * @return MutationType
     */
    public static function mutation()
    {
        return self::$mutation ?: (self::$mutation = new MutationType());
    }

    /*************** Singleton Pattern **************/
    private function __construct()
    {
        $config = [
            'name' => 'Mutation',
            'fields' => [],
            'resolveField' => function ($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            }
        ];
        parent::__construct($config);
    }

    private function searchFields(): array
    {
        return [];
    }
}
