<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server;

use Attribute;

#[Attribute]
class OpenApiServerPattern implements OpenApiServerAttribute
{
    public function __construct(public string $pattern) { }
}
