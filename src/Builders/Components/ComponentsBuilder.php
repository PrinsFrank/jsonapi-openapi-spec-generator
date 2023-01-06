<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Routing\Router;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Response\ResponseBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Schema\SchemaBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\SecuritySchemes\SecuritySchemesBuilderContract;

class ComponentsBuilder implements ComponentsBuilderContract
{
    public function __construct(
        private SchemaBuilderContract $schemaBuilder,
        private ResponseBuilderContract $responseBuilder,
        private SecuritySchemesBuilderContract $securitySchemesBuilder
    ) {
    }

    public function build(Server $server, Router $router, UrlGenerator $urlGenerator): ?Components
    {
        return (new Components())
            ->schemas(... $this->schemaBuilder->build($server))
            ->responses(... $this->responseBuilder->build($server, $router, $urlGenerator))
            ->securitySchemes(... $this->securitySchemesBuilder->build($server));
    }
}
