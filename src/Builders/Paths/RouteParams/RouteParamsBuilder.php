<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\RouteParams;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use Illuminate\Routing\Route;
use LaravelJsonApi\Core\Server\Server;
use LaravelJsonApi\Eloquent\Fields\Relations\Relation;

class RouteParamsBuilder implements RouteParamsBuilderContract
{
    /** @return Parameter[] */
    public function build(Server $server, Route $route): array
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

        $resourceType = $route->defaults['resource_type'] ?? null;
        if ($resourceType !== null && str_contains($route->getName() ?? '', 'index')) {
            $stringOrIntegerOrBoolean = (new AnyOf())->schemas(Schema::string(), Schema::integer(), Schema::boolean());

            $schema = $server->schemas()->schemaFor($resourceType);
            foreach ($schema->filters() as $filter) {
                $routeParams[] = (new Parameter())
                    ->in('query')
                    ->name('filter[' . $filter->key() . ']')
                    ->schema(
                        method_exists($filter, 'isSingular') && $filter->isSingular()
                            ? $stringOrIntegerOrBoolean
                            : Schema::array()->items($stringOrIntegerOrBoolean)
                    );
             }

             $includes = [];
	     foreach ($schema->relationships() as $relation) {
                 $includes[] = $relation->name();
	     }

             if ($includes !== []) {
                 $routeParams[] = (new Parameter())
                     ->in('query')
                     ->name('include')
                     ->schema(Schema::string()->enum(...$includes));
             }
        }

        return $routeParams;
    }
}
