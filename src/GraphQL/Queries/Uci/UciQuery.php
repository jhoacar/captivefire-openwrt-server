<?php

namespace App\GraphQL\Queries\Uci;

use App\GraphQL\ILoader;

/**
 * Class used for load all the uci type in GraphQL.
 */
class UciQuery implements ILoader
{
    /**
     * @var UciType
     */
    private static $uci;

    /**
     * @return UciType
     */
    private static function uci()
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
