<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Routing\Route as IlluminateRoute;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\RouteParams\RouteParamsBuilder;

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

            foreach ($route->methods as $method) {
                $operationsForUri[$route->uri()][] = (new Operation())->action(strtolower($method))->parameters(...RouteParamsBuilder::build($route));
            }
        }

        $pathItems = [];
        foreach ($operationsForUri as $uri => $operations) {
            $pathItems[] = (new PathItem())->route('/' . ltrim($uri, '/'))->operations(... $operations);
        }

        return $pathItems;
    }
}
