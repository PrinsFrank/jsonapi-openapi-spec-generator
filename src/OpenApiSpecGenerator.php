<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Contracts\Config\Repository;
use LaravelJsonApi\Core\Server\Server;
use LaravelJsonApi\Core\Support\AppResolver;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\ComponentsBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\ExternalDocs\ExternalDocsBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Info\InfoBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\PathsBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Security\SecurityBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\ServersBuilderContract;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\ClassNotFoundException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\InvalidConfigurationException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\InvalidServerException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\JsonapiOpenapiSpecGeneratorException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\MissingConfigurationException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\VersionNotFoundException;

class OpenApiSpecGenerator
{
    public function __construct(
        private AppResolver                 $appResolver,
        private Repository                  $configRepository,
        private ExternalDocsBuilderContract $externalDocsBuilder,
        private InfoBuilderContract         $infoBuilder,
        private ServersBuilderContract      $serversBuilder,
        private SecurityBuilderContract     $securityBuilder,
        private PathsBuilderContract        $pathsBuilder,
        private ComponentsBuilderContract   $componentsBuilder
    ) {
    }

    /**
     * @throws JsonapiOpenapiSpecGeneratorException
     */
    public function generate(string $serverName): OpenApi
    {
        $apiVersions = $this->configRepository->get('jsonapi.servers');
        if (is_array($apiVersions) === false) {
            throw new MissingConfigurationException('No api versions configured for jsonapi.servers configuration key');
        }

        $apiVersionFQN = $this->configRepository->get('jsonapi.servers.' . $serverName);
        if ($apiVersionFQN === null) {
            throw new VersionNotFoundException('No api server configured with name "' . $serverName . '"');
        }

        if (is_string($apiVersionFQN) === false) {
            throw new InvalidConfigurationException('Expected a FQN for "jsonapi.servers.' . $serverName . '" config key, got "' . gettype($apiVersionFQN) . '"');
        }

        if (class_exists($apiVersionFQN) === false) {
            throw new ClassNotFoundException('Api server class "' . $apiVersionFQN . '" for "' . $serverName . '" doesn\'t exist');
        }

        $server = new $apiVersionFQN($this->appResolver, $serverName);
        if ($server instanceof Server === false) {
            throw new InvalidServerException('Server is not an instance of "' . Server::class . '"');
        }

        return OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_2)
            ->externalDocs($this->externalDocsBuilder->build($server))
            ->info($this->infoBuilder->build($server))
            ->servers(...$this->serversBuilder->build($server))
            ->security(...$this->securityBuilder->build($server))
            ->paths(...$this->pathsBuilder->build($server))
            ->components($this->componentsBuilder->build($server));
    }
}
