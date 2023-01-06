<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Illuminate\Routing\Route;
use LaravelJsonApi\Core\Server\Server;

interface ResponsesBuilderContract
{
    /** @return Response[] */
    public function build(Server $server, Route $route): array;
}
