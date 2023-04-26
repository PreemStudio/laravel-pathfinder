<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Support\Facades\Route;
use BombenProdukt\Pathfinder\Pathfinder;

it('should parse a route path', function (string $verb): void {
    $method = "{$verb}Matched";
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
