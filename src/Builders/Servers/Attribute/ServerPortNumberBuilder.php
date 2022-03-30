<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Attribute;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ServerVariable;
use InvalidArgumentException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerAttribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerPortNumber;

class ServerPortNumberBuilder implements ServerAttributeBuilder
{
    public const OBJECT_ID = 'port_number';

    public static function build(ServerDocumentation $documentation, OpenApiServerAttribute $attribute)
    {
        if ($attribute instanceof OpenApiServerPortNumber === false) {
            throw new InvalidArgumentException(OpenApiServerPortNumber::class . ' expected, ' . $attribute::class . ' given.');
        }

        return (new ServerVariable())
            ->objectId(static::OBJECT_ID)
            ->enum(...array_map('strval', $attribute->portNumbers))
            ->default((string)array_values($attribute->portNumbers)[0])
            ->description('The protocol to be used');
    }
}
