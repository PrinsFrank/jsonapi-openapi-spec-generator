<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Routing\Router;
use LaravelJsonApi\Core\Server\Server;

interface PathsBuilderContract
{
    /** @return PathItem[] */
    public function build(Server $server, Router $router, UrlGenerator $urlGenerator): array;
}
