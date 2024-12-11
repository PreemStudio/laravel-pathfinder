<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests\Unit;

use BaseCodeOy\Pathfinder\RouteSchema;

it('should parse a route path', function (): void {
    $routeSchema = RouteSchema::fromString('/packages/{package}/{version}');

    expect($routeSchema->path)->toBe('/packages/{package}/{version}');
    expect($routeSchema->parameters)->toBe(['package' => 'string', 'version' => 'string']);
});

it('should parse a route path with an optional parameter', function (): void {
    $routeSchema = RouteSchema::fromString('/packages/{package}/{version}/{path?}');

    expect($routeSchema->path)->toBe('/packages/{package}/{version}/{path?}');
    expect($routeSchema->parameters)->toBe(['package' => 'string', 'version' => 'string', 'path' => 'string']);
});

it('should parse a route path with an optional parameter with a type', function (): void {
    $routeSchema = RouteSchema::fromString('/packages/{package}/{version}/{path:number?}');

    expect($routeSchema->path)->toBe('/packages/{package}/{version}/{path?}');
    expect($routeSchema->parameters)->toBe(['package' => 'string', 'version' => 'string', 'path' => 'number']);
});

it('should extract parameters from a route path', function (string $type): void {
    $routeSchema = RouteSchema::fromString(\sprintf('/packages/{package:%s}', $type));

    expect($routeSchema->path)->toBe('/packages/{package}');
    expect($routeSchema->parameters)->toBe(['package' => $type]);
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
    $routeSchema = RouteSchema::fromColon('/packages/:package(a,b,c)/:version/:path(number)?');

    expect($routeSchema->path)->toBe('/packages/{package}/{version}/{path?}');
    expect($routeSchema->parameters)->toBe(['package' => 'a,b,c', 'version' => 'string', 'path' => 'number']);
});
