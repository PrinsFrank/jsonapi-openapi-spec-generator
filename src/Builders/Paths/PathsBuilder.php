<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Routing\Route as IlluminateRoute;
use LaravelJsonApi\Core\Server\Server;

class PathsBuilder
{
    /** @return PathItem[] */
    public function build(Server $server, RouteFacade $router, UrlGenerator $urlGenerator): array
    {
        $operationsForUri = [];
        foreach ($router::getRoutes() as $route) {
            /** @var IlluminateRoute $route */
            if (str_starts_with($urlGenerator->to($route->uri()), $server->url()) === false) {
                continue;
            }

            preg_match_all('/{[A-z0-9]+}/', $route->uri(), $routeParamNames, PREG_PATTERN_ORDER);
            $routeParams = [];
            foreach ($routeParamNames[0] as $routeParamName) {
                $routeParams[] = (new Parameter())
                    ->in('path')
                    ->name(trim($routeParamName, '{}'))
                    ->schema((new Schema())->type('integer'))
                    ->required(true);
            }

            foreach ($route->methods as $method) {
                $operationsForUri[$route->uri()][] = (new Operation())->action(strtolower($method))->parameters(... $routeParams);
            }
        }

        $pathItems = [];
        foreach ($operationsForUri as $uri => $operations) {
            $pathItems[] = (new PathItem())
                ->route('/' . ltrim($uri, '/'))
                ->operations(... $operations);
        }

        return $pathItems;
    }
}
