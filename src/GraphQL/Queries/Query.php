<?php 

namespace App\GraphQL\Queries;


interface Query{
    /**
     * This is method that return an array for queries in GraphQL
     */
    public static function getQueries(): array;
}