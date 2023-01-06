<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Routing\Router;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Response\ResponseBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Schema\SchemaBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Security\SecuritySchemesBuilder;

class ComponentsBuilder
{
    public function build(Server $server, Router $router, UrlGenerator $urlGenerator): ?Components
    {
        return (new Components())
            ->schemas(... SchemaBuilder::build($server))
            ->responses(... ResponseBuilder::build($server, $router, $urlGenerator))
            ->securitySchemes(... SecuritySchemesBuilder::build($server));
    }
}
