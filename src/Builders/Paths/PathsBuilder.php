<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Routing\Route as IlluminateRoute;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Attribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Controller\Method\OpenApiUnauthenticatedRoute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Controller\Method\OpenApiHideMethod;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Controller\OpenApiHideController;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Controller\OpenApiTag;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\Responses\ResponsesBuilder;
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

            if (Attribute::classHas($route->getController(), OpenApiHideController::class) || Attribute::methodHas($route->getController(), $route->getActionMethod(), OpenApiHideMethod::class)) {
                continue;
            }

            $customTag = Attribute::classGet($route->getController(), OpenApiTag::class);
            foreach ($route->methods as $method) {
                if (strtoupper($method) === 'HEAD') {
                    continue;
                }

                $operation = Operation::create()
                    ->tags($customTag !== null ? $customTag->tagName : str_replace('-', ' ', ucfirst($route->defaults['resource_type'] ?? 'Default')))
                    ->action(strtolower($method))
                    ->parameters(...RouteParamsBuilder::build($server, $route))
                    ->responses(...ResponsesBuilder::build($server, $route));

                if (Attribute::methodHas($route->getController(), $route->getActionMethod(), OpenApiUnauthenticatedRoute::class)) {
                    $operation = $operation->noSecurity();
                }

                $operationsForUri[str_replace($server->url(), '', $urlGenerator->to($route->uri()))][] = $operation;
            }
        }

        $pathItems = [];
        foreach ($operationsForUri as $uri => $operations) {
            $pathItems[] = (new PathItem())->route('/' . ltrim($uri, '/'))->operations(... $operations);
        }

        return $pathItems !== [] ? $pathItems : [(new PathItem())->route('/')->description('No paths configured yet')];
    }
}
