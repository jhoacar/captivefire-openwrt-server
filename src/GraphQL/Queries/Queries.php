<?php 

namespace App\GraphQL;


interface Queries{
    /**
     * This is method that return an array for queries in GraphQL
     */
    public static function getQueries(): array;
}