<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ServerVariable;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerAttribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerBaseUri;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerDomain;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerEnvironment;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerPattern;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerPortNumber;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerProtocol;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\AttributeNotSupportedException;
use ReflectionClass;

class ServersBuilder
{
    public const SERVER_PATTERN = '{' . OpenApiServerProtocol::OBJECT_ID . '://}{' . OpenApiServerEnvironment::OBJECT_ID . '.}{' . OpenApiServerDomain::OBJECT_ID . '}{:' . OpenApiServerPortNumber::OBJECT_ID . '}{' . OpenApiServerBaseUri::OBJECT_ID . '}';

    /**
     * @return ServerDocumentation[]
     * @throws AttributeNotSupportedException
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
            if ($attribute instanceof OpenApiServerAttribute === false) {
                continue;
            }

            if ($attribute instanceof OpenApiServerPattern) {
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
