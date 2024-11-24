<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pathfinder;

use BaseCodeOy\PackagePowerPack\Package\AbstractServiceProvider;
use Illuminate\Routing\Route as BoundRoute;
use Illuminate\Support\Facades\Route;

final class ServiceProvider extends AbstractServiceProvider
{
    private array $verbs = [
        'delete',
        'get',
        'patch',
        'post',
        'put',
    ];

    public function packageRegistered(): void
    {
        foreach ($this->verbs as $verb) {
            Route::macro("{$verb}Matched", function (string $uri, array|string|callable|null $action = null) use ($verb): BoundRoute {
                $route = Route::$verb($uri, $action);

                Pathfinder::apply($uri, $route);

                return $route;
            });
        }
    }
}
