<?php

namespace App\GraphQL;


interface Mutations
{
    /**
     * This is a method to return the mutations in GraphQL
     */
    public static function getMutations(): array;
}
