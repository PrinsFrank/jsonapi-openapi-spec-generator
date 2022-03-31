<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Attribute;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ServerVariable;
use InvalidArgumentException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerAttribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerDomain;

class ServerDomainBuilder implements ServerAttributeBuilder
{
    public const OBJECT_ID = 'domain';

    public static function build(ServerDocumentation $documentation, OpenApiServerAttribute $attribute)
    {
        if ($attribute instanceof OpenApiServerDomain === false) {
            throw new InvalidArgumentException(OpenApiServerDomain::class . ' expected, ' . $attribute::class . ' given.');
        }

        return (new ServerVariable())
            ->objectId(static::OBJECT_ID)
            ->enum(...$attribute->domains)
            ->default(array_values($attribute->domains)[0])
            ->description('The domain to be used');
    }
}
