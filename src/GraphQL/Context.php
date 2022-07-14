<?php

/*
 * This file is part of the Captivefire package.
 *
 * (c) Jhoan Carrero <jhoacar@captivefire.net>
 *
 */

namespace App\GraphQL;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class used by send all the information inside each resolver for GraphQL.
 * 
 * @author Jhoan Carrero <jhoacar@captivefire.net>
 * 
 * @api
 */
class Context
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    public $request;

    /**
     * Constructor.
     * @param void
     * 
     * @api
     */
    public function __construct()
    {
    }
}
