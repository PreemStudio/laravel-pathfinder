<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests\Unit;

use BaseCodeOy\Pathfinder\Pathfinder;
use Illuminate\Support\Facades\Route;

it('should parse a route path', function (string $verb): void {
    $method = $verb.'Matched';
    $route = Route::$method('/packages/{package}/{version}/{path:number?}', fn (): array => []);

    Pathfinder::apply('/packages/{package}/{version}/{path:number?}', $route);

    expect($route->uri)->toBe('packages/{package}/{version}/{path?}');
    expect($route->wheres)->toBe(['path' => '[0-9]+']);
})->with([
    'delete',
    'get',
    'patch',
    'post',
    'put',
]);
