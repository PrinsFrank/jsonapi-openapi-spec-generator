<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\RouteParams;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Routing\Route;
use LaravelJsonApi\Core\Server\Server;
use LaravelJsonApi\Eloquent\Filters\Concerns\IsSingular;

class RouteParamsBuilder
{
    /** @return Parameter[] */
    public static function build(Server $server, Route $route): array
    {
        preg_match_all('/{[A-z0-9]+}/', $route->uri(), $routeParamNames, PREG_PATTERN_ORDER);
        $routeParams = [];
        foreach ($routeParamNames[0] as $routeParamName) {
            $routeParams[] = (new Parameter())
                ->in('path')
                ->name(trim($routeParamName, '{}'))
                ->schema((new Schema())->type('integer'))
                ->required();
        }

        if (isset($route->defaults['resource_type']) && str_contains($route->getName(), 'index')) {
            $schema = $server->schemas()->schemaFor($route->defaults['resource_type']);
            foreach ($schema->filters() as $filter) {
                $routeParams[] = (new Parameter())
                    ->in('query')
                    ->name('filter[' . $filter->key() . ']')
                    ->schema($filter->isSingular() ? Schema::string() : (new Schema())->type('array'));
            }
        }

        return $routeParams;
    }
}
