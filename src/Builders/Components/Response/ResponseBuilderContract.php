<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Response;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Routing\Router;
use LaravelJsonApi\Core\Server\Server;

interface ResponseBuilderContract
{
    /** @return Response[] */
    public function build(Server $server, Router $router, UrlGenerator $urlGenerator): array;
}
