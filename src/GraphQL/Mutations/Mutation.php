<?php

namespace App\GraphQL\Mutations;


interface Mutation
{
    /**
     * This is a method to return the mutations in GraphQL
     */
    public function __invoke(): array;
}
