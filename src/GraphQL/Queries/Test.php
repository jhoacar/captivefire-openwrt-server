<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;

use App\GraphQL\Queries;

// We import the file for resolvers, if we write 'use' don't import anything without autoloader

class Test implements Queries
{
    
    public static function getQueries(): array
    {
        
        $messageResolver = require_once 'Resolvers/Message.php'; 
        return [
            'echo' => [
                'type' => Type::string(),
                'args' => [
                    'message' => Type::nonNull(Type::string()),
                ],
                'resolve' => $messageResolver,
            ],
        ];
    }
}

