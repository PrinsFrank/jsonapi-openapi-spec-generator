<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ServerVariable;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiPathBaseUri;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiPathDomain;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiPathAttribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiPathEnvironment;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiPathPattern;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiPathPortNumber;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiPathProtocol;
use ReflectionClass;
use ReflectionException;

class ServersBuilder
{
    public const SERVER_PATTERN = '{' . OpenApiPathProtocol::OBJECT_ID . '://}{' . OpenApiPathEnvironment::OBJECT_ID . '.}{' . OpenApiPathDomain::OBJECT_ID . '}{:' . OpenApiPathPortNumber::OBJECT_ID . '}{' . OpenApiPathBaseUri::OBJECT_ID . '}';

    /**
     * @return ServerDocumentation[]
     * @throws ReflectionException
     */
    public function build(Server $server): array
    {
        $documentation = new ServerDocumentation();
        $serverPattern = static::SERVER_PATTERN;

        $variables = [];
        $reflectionServer = (new ReflectionClass($server));
        $baseUri = $reflectionServer->getProperty('baseUri')->getValue($server);
        foreach ($reflectionServer->getAttributes() as $reflectionAttribute) {
            $attribute = $reflectionAttribute->newInstance();
            if ($attribute instanceof OpenApiPathAttribute === false || count($attribute->enum) === 0) {
                continue;
            }

            if ($attribute instanceof OpenApiPathPattern) {
                $serverPattern = $attribute->pattern;

                continue;
            }

            $variables[] = (new ServerVariable())
                ->objectId($attribute->objectId())
                ->enum(...array_map('strval', $attribute->enum))
                ->default((string)array_values($attribute->enum)[0])
                ->description($attribute->description());
        }

        return [UrlBuilder::build($documentation, $variables, $serverPattern, $baseUri)];
    }
}
