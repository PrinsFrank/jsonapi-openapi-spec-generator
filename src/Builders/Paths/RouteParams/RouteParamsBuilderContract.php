<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\RouteParams;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use Illuminate\Routing\Route;
use LaravelJsonApi\Core\Server\Server;

interface RouteParamsBuilderContract
{
    /** @return Parameter[] */
    public function build(Server $server, Route $route): array;
}
