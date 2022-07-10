<?php

namespace App\GraphQL\Queries;

use App\GraphQL\Queries\IQuery;
use App\GraphQL\Queries\Uhttpd\UhttpdType;
use GraphQL\Type\Definition\ResolveInfo;

class Uhttpd implements IQuery
{

    private static $uhttpd;
    /**
     * @return UhttpdType
     */
    public static function uhttpd()
    {
        return self::$uhttpd ?: (self::$uhttpd = new UhttpdType());
    }
    

    public static function getFields(): array
    {
        return [
            'uhttpd' => [
                'type' => self::uhttpd()
            ]
        ];
    }
}
