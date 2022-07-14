<?php

namespace App\GraphQL\Mutations;

use App\GraphQL\Loader;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class MutationType extends ObjectType
{
    /*
     * This trait load all fields in each folder for this namespace
     */
    use Loader;

    private static $mutation;

    /**
     * Singleton Pattern.
     * @return MutationType
     */
    public static function mutation()
    {
        return self::$mutation ?: (self::$mutation = new self());
    }

    /*************** Singleton Pattern **************/
    private function __construct()
    {
        $this->namespace = __NAMESPACE__;
        $this->searchFields();
        $config = [
            'name' => 'Mutation',
            'fields' => [],
            'resolveField' => function ($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            },
        ];
        parent::__construct($config);
    }
}
