<?php

declare(strict_types=1);

namespace BaseCodeOy\Pathfinder;

use Spatie\Regex\Regex;

final class RouteSchema
{
    private function __construct(
        public readonly string $path,
        public readonly array $parameters,
    ) {
        //
    }

    /**
     * @example
     * /packages/{package}/{version}/{path:number?}
     */
    public static function fromString(string $uri): static
    {
        $parameters = [];

        $regex = Regex::matchAll('/\{([a-zA-Z0-9_:,?]+)\}/', $uri);

        foreach ($regex->results() as $result) {
            $group = $result->group(1);

            if (\str_contains($group, ':')) {
                [$name, $type] = \explode(':', $group, 2);

                $parameters[self::normalizeString($name)] = self::normalizeString($type);
            } else {
                $parameters[self::normalizeString($group)] = 'string';
            }
        }

        return new self(
            \preg_replace('/(:[a-zA-Z,]+)/', '', $uri),
            $parameters,
        );
    }

    /**
     * @internal
     * This is currently just an idea for a more flexible way to define routes
     *
     * @example
     * /packages/:package(a,b,c)/:version/:path(number)?
     */
    public static function fromColon(string $uri): static
    {
        $segments = collect(\explode('/', $uri));

        return new self(
            '/'.$segments->map(function (string $segment) {
                if (\str_starts_with($segment, ':')) {
                    return '{'.\preg_replace('/\([a-zA-Z,]+\)/', '', \mb_substr($segment, 1)).'}';
                }

                return $segment;
            })->filter()->join('/'),
            $segments
                ->filter(fn (string $segment): bool => \str_starts_with($segment, ':'))
                ->map(fn (string $segment): string => \mb_substr($segment, 1))
                ->mapWithKeys(function (string $segment): array {
                    $match = Regex::matchAll('/[a-zA-Z0-9_\\-\.,:;\+*^%\$@!]+/', $segment);

                    if (\count($match->results()) === 1) {
                        return [
                            $match->results()[0]->group(0) => 'string',
                        ];
                    }

                    return [
                        $match->results()[0]->group(0) => $match->results()[1]->group(0),
                    ];
                })
                ->toArray(),
        );
    }

    private static function normalizeString(string $value): string
    {
        return \preg_replace('/[^a-z0-9,]+/', '', $value);
    }
}
