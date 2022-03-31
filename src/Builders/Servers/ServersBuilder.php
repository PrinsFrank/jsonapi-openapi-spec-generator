<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerAttribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerDomain;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerEnvironment;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerPattern;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerPortNumber;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerProtocol;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Attribute\ServerDomainBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Attribute\ServerEnvironmentBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Attribute\ServerPortNumberBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Attribute\ServerProtocolBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\AttributeNotSupportedException;
use ReflectionClass;

class ServersBuilder
{
    public const SERVER_PATTERN = '{protocol://}{environment.}{domain}{:port_number}';

    /**
     * @return ServerDocumentation[]
     * @throws AttributeNotSupportedException
     */
    public function build(Server $server): array
    {
        $documentation = new ServerDocumentation();
        $serverPattern = static::SERVER_PATTERN;

        $variables = [];
        foreach ((new ReflectionClass($server))->getAttributes() as $reflectionAttribute) {
            $attribute = $reflectionAttribute->newInstance();
            if ($attribute instanceof OpenApiServerAttribute === false) {
                continue;
            }

            if ($attribute instanceof OpenApiServerPattern) {
                $serverPattern = $attribute->pattern;

                continue;
            }

            $variables[] = match (get_class($attribute)) {
                OpenApiServerEnvironment::class => ServerEnvironmentBuilder::build($documentation, $attribute),
                OpenApiServerPortNumber::class => ServerPortNumberBuilder::build($documentation, $attribute),
                OpenApiServerProtocol::class => ServerProtocolBuilder::build($documentation, $attribute),
                OpenApiServerDomain::class => ServerDomainBuilder::build($documentation, $attribute),
                default => throw new AttributeNotSupportedException(get_class($attribute))
            };
        }

        return [UrlBuilder::build($documentation, $variables, $serverPattern)];
    }
}
