<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Foundation\Application;
use LaravelJsonApi\Core\Server\Server;
use LaravelJsonApi\Core\Support\AppResolver;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Components\ComponentsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\ClassNotFoundException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\InvalidServerException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\MissingConfigurationException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\VersionNotFoundException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\ExternalDocs\ExternalDocsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Info\InfoBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Paths\PathsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Security\SecurityBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Servers\ServersBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Tags\TagsBuilder;

class OpenApiSpecGenerator
{
    public function __construct(
        private Application $application,
        private TagsBuilder $tagsBuilder,
        private ExternalDocsBuilder $externalDocsBuilder,
        private InfoBuilder $infoBuilder,
        private ServersBuilder $serversBuilder,
        private SecurityBuilder $securityBuilder,
        private PathsBuilder $pathsBuilder,
        private ComponentsBuilder $componentsBuilder
    ) { }

    /**
     * @throws MissingConfigurationException
     * @throws VersionNotFoundException
     * @throws InvalidServerException
     * @throws ClassNotFoundException
     */
    public function generate(string $serverName): OpenApi
    {
        $apiVersions = config('jsonapi.servers');
        if (is_array($apiVersions) === false) {
            throw new MissingConfigurationException('No api versions configured for jsonapi.servers configuration key');
        }

        $apiVersionFQN = config('jsonapi.servers.' . $serverName);
        if ($apiVersionFQN === null) {
            throw new VersionNotFoundException('No api server configured with name "' . $serverName . '"');
        }

        if (class_exists($apiVersionFQN) === false) {
            throw new ClassNotFoundException('Api server class "' . $apiVersionFQN . '" for "' . $serverName . '" doesn\'t exist');
        }

        $appResolver = $this->application->get(AppResolver::class);
        $server = new $apiVersionFQN($appResolver, $serverName);
        if ($server instanceof Server === false) {
            throw new InvalidServerException('Server is not an instance of "' . Server::class . '"');
        }

        return OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_2)
            ->tags(...$this->tagsBuilder->build())
            ->externalDocs($this->externalDocsBuilder->build())
            ->info($this->infoBuilder->build())
            ->servers(...$this->serversBuilder->build())
            ->security(...$this->securityBuilder->build())
            ->paths(...$this->pathsBuilder->build())
            ->components($this->componentsBuilder->build());
    }
}
