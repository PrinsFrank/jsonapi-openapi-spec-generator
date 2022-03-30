<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Attribute;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ServerVariable;
use InvalidArgumentException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerAttribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerEnvironment;

class ServerEnvironmentBuilder implements ServerAttributeBuilder
{
    public const OBJECT_ID = 'environment';

    public static function build(ServerDocumentation $documentation, OpenApiServerAttribute $attribute)
    {
        if ($attribute instanceof OpenApiServerEnvironment === false) {
            throw new InvalidArgumentException(OpenApiServerEnvironment::class . ' expected, ' . $attribute::class . ' given.');
        }

        return (new ServerVariable())
            ->objectId(static::OBJECT_ID)
            ->enum(...$attribute->environments)
            ->default(array_values($attribute->environments)[0])
            ->description('The environment to be used');
    }
}
