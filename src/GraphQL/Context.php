<?php

namespace App\GraphQL;

use Symfony\Component\HttpFoundation\Request;

class Context
{
    /**
     * @var Request
     */
    public $request;

    public function __construct()
    {
    }
}
