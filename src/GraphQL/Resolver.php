<?php

namespace App\GraphQL;

interface Resolver
{
    public function __invoke($rootValue, array $args): string;
}
