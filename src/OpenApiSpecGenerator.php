<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use LaravelJsonApi\Core\Server\Server;
use LaravelJsonApi\Core\Support\AppResolver;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\ComponentsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\ClassNotFoundException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\InvalidConfigurationException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\InvalidServerException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\JsonapiOpenapiSpecGeneratorException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\MissingConfigurationException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\VersionNotFoundException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\ExternalDocs\ExternalDocsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Info\InfoBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\PathsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Security\SecurityBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\ServersBuilder;

class OpenApiSpecGenerator
{
    public function __construct(
        private Application $application,
        private ExternalDocsBuilder $externalDocsBuilder,
        private InfoBuilder $infoBuilder,
        private ServersBuilder $serversBuilder,
        private SecurityBuilder $securityBuilder,
        private PathsBuilder $pathsBuilder,
        private ComponentsBuilder $componentsBuilder
    ) {
    }

    /**
     * @throws JsonapiOpenapiSpecGeneratorException
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

        if (is_string($apiVersionFQN) === false) {
            throw new InvalidConfigurationException('Expected a FQN for "jsonapi.servers.' . $serverName . '" config key, got "' . gettype($apiVersionFQN) . '"');
        }

        if (class_exists($apiVersionFQN) === false) {
            throw new ClassNotFoundException('Api server class "' . $apiVersionFQN . '" for "' . $serverName . '" doesn\'t exist');
        }

        $appResolver = $this->application->make(AppResolver::class);
        $server      = new $apiVersionFQN($appResolver, $serverName);
        if ($server instanceof Server === false) {
            throw new InvalidServerException('Server is not an instance of "' . Server::class . '"');
        }

        $router       = $this->application->make(Router::class);
        $urlGenerator = $this->application->make(UrlGenerator::class);
        return OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_2)
            ->externalDocs($this->externalDocsBuilder->build($server))
            ->info($this->infoBuilder->build($server))
            ->servers(...$this->serversBuilder->build($server))
            ->security(...$this->securityBuilder->build($server))
            ->paths(...$this->pathsBuilder->build($server, $router, $urlGenerator))
            ->components($this->componentsBuilder->build($server, $router, $urlGenerator));
    }
}
