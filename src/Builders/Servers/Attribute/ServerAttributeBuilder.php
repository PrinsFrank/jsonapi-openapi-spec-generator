<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Attribute;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\OpenApiServerAttribute;

interface ServerAttributeBuilder
{
    public static function build(ServerDocumentation $documentation, OpenApiServerAttribute $attribute);
}
