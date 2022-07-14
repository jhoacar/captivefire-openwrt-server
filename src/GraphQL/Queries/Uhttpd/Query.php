<?php

namespace App\GraphQL\Queries\Uhttpd;

use App\GraphQL\ILoader;

class Query implements ILoader
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
                'type' => self::uhttpd(),
            ],
        ];
    }
}
