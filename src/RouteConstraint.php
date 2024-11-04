<?php

declare(strict_types=1);

namespace BaseCodeOy\Pathfinder;

use Illuminate\Routing\Route;
use Illuminate\Support\Traits\Macroable;

final class RouteConstraint
{
    use Macroable;

    public static function apply(Route $route, string $type, string $parameter): void
    {
        if (self::hasMacro($type)) {
            self::$type($route, $parameter);

            return;
        }

        match (true) {
            $type === 'alpha' => $route->whereAlpha($parameter),
            $type === 'alphanumeric' => $route->whereAlphaNumeric($parameter),
            $type === 'number' => $route->whereNumber($parameter),
            $type === 'string' => null,
            $type === 'ulid' => $route->whereUlid($parameter),
            $type === 'uuid' => $route->whereUuid($parameter),
            $type === 'wildcard' => $route->where($parameter, '.*'),
            \str_contains($type, ',') => $route->whereIn($parameter, \explode(',', $type)),
            default => $route->where($parameter, $type),
        };
    }
}
