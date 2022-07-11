<?php

namespace App\GraphQL\Queries\Uhttpd\Resolvers;

use GraphQL\Type\Definition\ResolveInfo;

return function ($value, $args, $context, ResolveInfo $info): string {
    return "80 papi";
};
