<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Attribute;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ServerVariable;
use InvalidArgumentException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerAttribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerProtocol;

class ServerProtocolBuilder implements ServerAttributeBuilder
{
    public const OBJECT_ID = 'protocol';

    public static function build(ServerDocumentation $documentation, OpenApiServerAttribute $attribute): ?ServerVariable
    {
        if ($attribute instanceof OpenApiServerProtocol === false) {
            throw new InvalidArgumentException(OpenApiServerProtocol::class . ' expected, ' . $attribute::class . ' given.');
        }

        return (new ServerVariable())
            ->objectId(static::OBJECT_ID)
            ->enum(...$attribute->protocols)
            ->default(array_values($attribute->protocols)[0])
            ->description('The protocol to be used');
    }
}
