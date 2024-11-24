<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests\Unit;

use BaseCodeOy\Pathfinder\RouteConstraint;
use Illuminate\Support\Facades\Route;

it('should apply the [alpha] constraint', function (): void {
    $route = Route::view('/packages/{package}/{version}/{path?}', 'view');

    expect($route->wheres)->toBeEmpty();

    RouteConstraint::apply($route, 'alpha', 'package');

    expect($route->wheres)->toBe(['package' => '[a-zA-Z]+']);
});

it('should apply the [alphanumeric] constraint', function (): void {
    $route = Route::view('/packages/{package}/{version}/{path?}', 'view');

    expect($route->wheres)->toBeEmpty();

    RouteConstraint::apply($route, 'alphanumeric', 'package');

    expect($route->wheres)->toBe(['package' => '[a-zA-Z0-9]+']);
});

it('should apply the [number] constraint', function (): void {
    $route = Route::view('/packages/{package}/{version}/{path?}', 'view');

    expect($route->wheres)->toBeEmpty();

    RouteConstraint::apply($route, 'number', 'package');

    expect($route->wheres)->toBe(['package' => '[0-9]+']);
});

it('should apply the [string] constraint', function (): void {
    $route = Route::view('/packages/{package}/{version}/{path?}', 'view');

    expect($route->wheres)->toBeEmpty();

    RouteConstraint::apply($route, 'string', 'package');

    expect($route->wheres)->toBeEmpty();
});

it('should apply the [ulid] constraint', function (): void {
    $route = Route::view('/packages/{package}/{version}/{path?}', 'view');

    expect($route->wheres)->toBeEmpty();

    RouteConstraint::apply($route, 'ulid', 'package');

    expect($route->wheres)->toBe(['package' => '[0-7][0-9a-hjkmnp-tv-zA-HJKMNP-TV-Z]{25}']);
});

it('should apply the [uuid] constraint', function (): void {
    $route = Route::view('/packages/{package}/{version}/{path?}', 'view');

    expect($route->wheres)->toBeEmpty();

    RouteConstraint::apply($route, 'uuid', 'package');

    expect($route->wheres)->toBe(['package' => '[\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}']);
});

it('should apply the [wildcard] constraint', function (): void {
    $route = Route::view('/packages/{package}/{version}/{path?}', 'view');

    expect($route->wheres)->toBeEmpty();

    RouteConstraint::apply($route, 'wildcard', 'package');

    expect($route->wheres)->toBe(['package' => '.*']);
});

it('should apply macro constraints', function (): void {
    $route = Route::view('/packages/{package}/{version}/{path?}', 'view');

    expect($route->wheres)->toBeEmpty();

    RouteConstraint::macro('zero', fn ($route, string $parameter) => $route->where($parameter, '0x0'));
    RouteConstraint::apply($route, 'zero', 'package');

    expect($route->wheres)->toBe(['package' => '0x0']);
});
