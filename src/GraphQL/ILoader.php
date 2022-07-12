<?php

namespace App\GraphQL;

interface ILoader
{
    /**
     * This is a method to resolve the fields
     */
    public static function getFields(): array;
}
