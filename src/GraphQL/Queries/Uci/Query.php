<?php

namespace App\GraphQL\Queries;

use App\GraphQL\ILoader;
use App\GraphQL\Queries\Uci\UciType;

class Uci implements ILoader
{
    private static $uci;
    /**
     * @return UciType
     */
    public static function uci()
    {
        return self::$uci ?: (self::$uci = new UciType());
    }


    public static function getFields(): array
    {
        return [
            'uci' => [
                'type' => self::uci()
            ]
        ];
    }
}
