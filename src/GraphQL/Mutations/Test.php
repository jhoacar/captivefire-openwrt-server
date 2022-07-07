<?php

namespace App\GraphQL\Mutations;

use App\GraphQL\Mutations;
use GraphQL\Type\Definition\Type;

class Test implements Mutations
{

    public static function getMutations(): array
    {
        return [
            'echo' => [
                'type' => Type::string(),
                'args' => [
                    'message' => Type::nonNull(Type::string()),
                ],
                'resolve' => fn ($rootValue, array $args): string => 'Mutation ' . $rootValue['prefix'] . $args['message'],
            ],
        ];
    }
}
