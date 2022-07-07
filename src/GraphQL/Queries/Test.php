<?php

namespace App\GraphQL\Queries;

use App\GraphQL\Queries;
use GraphQL\Type\Definition\Type;

class Test implements Queries
{

    public static function getQueries(): array
    {
        return [
            'echo' => [
                'type' => Type::string(),
                'args' => [
                    'message' => Type::nonNull(Type::string()),
                ],
                'resolve' => fn ($rootValue, array $args): string => $rootValue['prefix'] . $args['message'],
            ],
        ];
    }
}
