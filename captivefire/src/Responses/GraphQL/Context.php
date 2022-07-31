<?php

declare(strict_types=1);
/*
 * This file is part of the Captivefire package.
 *
 * (c) Jhoan Carrero <jhoacar@captivefire.net>
 *
 */

namespace App\Responses\GraphQL;

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
