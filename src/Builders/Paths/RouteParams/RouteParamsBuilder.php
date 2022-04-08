<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\RouteParams;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Routing\Route;

class RouteParamsBuilder
{
    /** @return Parameter[] */
    public static function build(Route $route): array
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

        return $routeParams;
    }
}
