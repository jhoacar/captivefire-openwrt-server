<?php

use GraphQL\GraphQL;
use GraphQL\Type\Schema;

require_once('types.php');
require_once('query.php');
require_once('mutations.php');

$schema = new Schema([
    'query' => $rootQuery,
    'mutation' => $rootMutation
]);

try {
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'];
    $result = GraphQL::executeQuery($schema, $query);

    $output = $result->toArray();
} catch(\Exception $e) {
    $output = [
        'error' => [
            'message' => $e->getMessage()
        ]
    ];
}


header('Content-Type: application/json');
echo json_encode($output);
