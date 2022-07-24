<?php

/*
 * This file is part of the Captivefire package.
 *
 * (c) Jhoan Carrero <jhoacar@captivefire.net>
 *
 */

namespace App\GraphQL;

use UciGraphQL\Context as UciGraphQLContext;

/**
 * Context represents all the information inside each resolver for GraphQL.
 *
 * @author Jhoan Carrero <jhoacar@captivefire.net>
 */
class Context extends UciGraphQLContext
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    public $request;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }
}
