<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pathfinder;

use Illuminate\Routing\Route;

final class Pathfinder
{
    public static function apply(string $uri, Route $route): Route
    {
        $routeSchema = RouteSchema::fromString($uri);

        foreach ($routeSchema->parameters as $parameter => $type) {
            RouteConstraint::apply($route, $type, $parameter);
        }

        return $route;
    }
}
