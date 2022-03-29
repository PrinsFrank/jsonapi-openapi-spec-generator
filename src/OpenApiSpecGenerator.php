<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Components\ComponentsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\ExternalDocs\ExternalDocsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Info\InfoBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Paths\PathsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Security\SecurityBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Servers\ServersBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Tags\TagsBuilder;

class OpenApiSpecGenerator
{
    public function __construct(
        private TagsBuilder $tagsBuilder,
        private ExternalDocsBuilder $externalDocsBuilder,
        private InfoBuilder $infoBuilder,
        private ServersBuilder $serversBuilder,
        private SecurityBuilder $securityBuilder,
        private PathsBuilder $pathsBuilder,
        private ComponentsBuilder $componentsBuilder
    ) { }

    public function generate(): OpenApi
    {
        return OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_2)
            ->tags(...$this->tagsBuilder->build())
            ->externalDocs(...$this->externalDocsBuilder->build())
            ->info(...$this->infoBuilder->build())
            ->servers(...$this->serversBuilder->build())
            ->security(...$this->securityBuilder->build())
            ->paths(...$this->pathsBuilder->build())
            ->components(...$this->componentsBuilder->build());
    }
}
