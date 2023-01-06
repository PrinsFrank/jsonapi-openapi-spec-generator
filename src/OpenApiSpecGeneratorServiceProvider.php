<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\ComponentsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\ComponentsBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Response\ResponseBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Response\ResponseBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Schema\SchemaBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Schema\SchemaBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\SecuritySchemes\SecuritySchemesBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\SecuritySchemes\SecuritySchemesBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\ExternalDocs\ExternalDocsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\ExternalDocs\ExternalDocsBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Info\InfoBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Info\InfoBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\PathsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\PathsBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\Responses\ResponsesBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\Responses\ResponsesBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\RouteParams\RouteParamsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\RouteParams\RouteParamsBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Security\SecurityBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Security\SecurityBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\ServersBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\ServersBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Url\UrlBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Url\UrlBuilderContract;

class OpenApiSpecGeneratorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(ResponseBuilderContract::class, ResponseBuilder::class);
        $this->app->bind(SchemaBuilderContract::class, SchemaBuilder::class);
        $this->app->bind(SecuritySchemesBuilderContract::class, SecuritySchemesBuilder::class);
        $this->app->bind(ComponentsBuilderContract::class, ComponentsBuilder::class);
        $this->app->bind(ExternalDocsBuilderContract::class, ExternalDocsBuilder::class);
        $this->app->bind(InfoBuilderContract::class, InfoBuilder::class);
        $this->app->bind(ResponsesBuilderContract::class, ResponsesBuilder::class);
        $this->app->bind(RouteParamsBuilderContract::class, RouteParamsBuilder::class);
        $this->app->bind(PathsBuilderContract::class, PathsBuilder::class);
        $this->app->bind(SecurityBuilderContract::class, SecurityBuilder::class);
        $this->app->bind(UrlBuilderContract::class, UrlBuilder::class);
        $this->app->bind(ServersBuilderContract::class, ServersBuilder::class);
    }

    /**
     * @return array<string|class-string>
     */
    public function provides(): array
    {
        return [
            ResponseBuilderContract::class,
            SchemaBuilderContract::class,
            SecuritySchemesBuilderContract::class,
            ComponentsBuilderContract::class,
            ExternalDocsBuilderContract::class,
            InfoBuilderContract::class,
            ResponsesBuilderContract::class,
            RouteParamsBuilderContract::class,
            PathsBuilderContract::class,
            SecurityBuilderContract::class,
            UrlBuilderContract::class,
            ServersBuilderContract::class,
        ];
    }
}
