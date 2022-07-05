<?php

use GraphQL\Type\Definition\ObjectType;


$mutations = array();

$rootMutation = new ObjectType([
    'name' => 'Mutation',
    'fields' => $mutations
]);