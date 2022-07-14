<?php

namespace App\GraphQL;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class used by send all the information inside each resolver for GraphQL.
 */
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
