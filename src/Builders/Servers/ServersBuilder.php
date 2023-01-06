<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ServerVariable;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Attribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path\OpenApiPathBaseUri;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path\OpenApiPathDomain;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path\OpenApiPathAttribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path\OpenApiPathEnvironment;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path\OpenApiPathPattern;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path\OpenApiPathPortNumber;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path\OpenApiPathProtocol;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Url\UrlBuilderContract;
use ReflectionClass;
use ReflectionException;

class ServersBuilder implements ServersBuilderContract
{
    public const SERVER_PATTERN = '{' . OpenApiPathProtocol::OBJECT_ID . '://}{' . OpenApiPathEnvironment::OBJECT_ID . '.}{' . OpenApiPathDomain::OBJECT_ID . '}{:' . OpenApiPathPortNumber::OBJECT_ID . '}{' . OpenApiPathBaseUri::OBJECT_ID . '}';

    public function __construct(
        private UrlBuilderContract $urlBuilder
    ) {
    }

    /**
     * @return ServerDocumentation[]
     * @throws ReflectionException
     */
    public function build(Server $server): array
    {
        $documentation = new ServerDocumentation();
        $serverPattern = static::SERVER_PATTERN;

        $variables   = [];
        $baseUriProp = (new ReflectionClass($server))->getProperty('baseUri');
        $baseUriProp->setAccessible(true);
        /** @var string $baseUri */
        $baseUri = $baseUriProp->getValue($server);
        foreach (Attribute::allForClass($server) as $reflectionAttribute) {
            $attribute = $reflectionAttribute->newInstance();
            if ($attribute instanceof OpenApiPathAttribute === false || property_exists($attribute, 'enum') === false || count($attribute->enum) === 0) {
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

        return [$this->urlBuilder->build($documentation, $variables, $serverPattern, $baseUri)];
    }
}
