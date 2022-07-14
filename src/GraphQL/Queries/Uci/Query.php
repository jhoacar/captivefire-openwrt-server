<?php

namespace App\GraphQL\Queries;

use App\GraphQL\ILoader;
use App\GraphQL\Queries\Uci\UciType;

/**
 * Class used for load all the uci type in GraphQL.
 */
class Query implements ILoader
{
    /**
     * @var UciType
     */
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
                'type' => self::uci(),
            ],
        ];
    }
}
