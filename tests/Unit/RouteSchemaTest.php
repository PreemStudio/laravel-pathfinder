<?php

declare(strict_types=1);

namespace Tests\Unit;

use BaseCodeOy\Pathfinder\RouteSchema;

it('should parse a route path', function (): void {
    $route = RouteSchema::fromString('/packages/{package}/{version}');

    expect($route->path)->toBe('/packages/{package}/{version}');
    expect($route->parameters)->toBe(['package' => 'string', 'version' => 'string']);
});

it('should parse a route path with an optional parameter', function (): void {
    $route = RouteSchema::fromString('/packages/{package}/{version}/{path?}');

    expect($route->path)->toBe('/packages/{package}/{version}/{path?}');
    expect($route->parameters)->toBe(['package' => 'string', 'version' => 'string', 'path' => 'string']);
});

it('should parse a route path with an optional parameter with a type', function (): void {
    $route = RouteSchema::fromString('/packages/{package}/{version}/{path:number?}');

    expect($route->path)->toBe('/packages/{package}/{version}/{path?}');
    expect($route->parameters)->toBe(['package' => 'string', 'version' => 'string', 'path' => 'number']);
});

it('should extract parameters from a route path', function (string $type): void {
    $route = RouteSchema::fromString("/packages/{package:{$type}}");

    expect($route->path)->toBe('/packages/{package}');
    expect($route->parameters)->toBe(['package' => $type]);
})->with([
    'a,b,c', // array
    'alpha',
    'alphanumeric',
    'number',
    'string',
    'ulid',
    'uuid',
    'wildcard',
]);

it('fromColon', function (): void {
    $route = RouteSchema::fromColon('/packages/:package(a,b,c)/:version/:path(number)?');

    expect($route->path)->toBe('/packages/{package}/{version}/{path?}');
    expect($route->parameters)->toBe(['package' => 'a,b,c', 'version' => 'string', 'path' => 'number']);
});
