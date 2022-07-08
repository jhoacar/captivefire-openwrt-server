<?php

namespace App\GraphQL\Mutations;


interface Mutation
{
    /**
     * This is a method to return the mutations in GraphQL
     */
    public static function getMutations(): array;
}
