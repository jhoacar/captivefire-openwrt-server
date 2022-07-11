<?php

namespace App\GraphQL\Queries\Uhttpd;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class UhttpdType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'uhttpd',
            'description' => 'Server Configuration',
            'fields' => function () {
                return [
                    'listen_http' => $this->listen_http(),
                ];
            },
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                $method = ucfirst($info->fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($value, $args, $context, $info);
                } else {
                    return $value->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }

    private function listen_http()
    {
        $resolver = require_once 'Resolvers/ListenHttp.php';
        return [
            'name' => 'listen_http',
            'type' => Type::string(),
            'description' => 'Port for HTTP Request',
            'resolve' => $resolver,
        ];
    }
}
