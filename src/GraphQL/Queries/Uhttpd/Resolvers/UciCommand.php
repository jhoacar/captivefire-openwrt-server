<?php

namespace App\GraphQL\Queries\Uhttpd\Resolvers;

use App\Utils\UciCommand;
use GraphQL\Type\Definition\ResolveInfo;

return function ($value, $args, $context, ResolveInfo $info): string {
    return UciCommand::get($info->parentType, 'captivefire', $info->fieldName);
};
