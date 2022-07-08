<?php

namespace App\GraphQL\Mutations;

use App\GraphQL\Mutations\Mutation;
use GraphQL\Type\Definition\Type;

class Test implements Mutation
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
