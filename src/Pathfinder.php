<?php

declare(strict_types=1);

namespace BombenProdukt\Pathfinder;

use Illuminate\Routing\Route;

final class Pathfinder
{
    public static function apply(string $uri, Route $route): Route
    {
        $schema = RouteSchema::fromString($uri);

        foreach ($schema->parameters as $parameter => $type) {
            RouteConstraint::apply($route, $type, $parameter);
        }

        return $route;
    }
}
