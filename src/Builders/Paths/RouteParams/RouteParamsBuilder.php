<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\RouteParams;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Routing\Route;
use LaravelJsonApi\Core\Server\Server;
use LaravelJsonApi\Laravel\Routing\Route as JsonApiRoute;

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
                ->schema(Schema::integer())
                ->required();
        }

	$resourceType = $route->parameter(JsonApiRoute::RESOURCE_TYPE);
        if ($resourceType !== null && str_contains($route->getName(), 'index')) {
            $schema = $server->schemas()->schemaFor($resourceType);
            foreach ($schema->filters() as $filter) {
                $routeParams[] = (new Parameter())
                    ->in('query')
                    ->name('filter[' . $filter->key() . ']')
                    ->schema($filter->isSingular() ? Schema::string() : Schema::array());
            }
        }

        return $routeParams;
    }
}
