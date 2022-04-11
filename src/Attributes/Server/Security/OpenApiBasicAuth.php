<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Security;

use Attribute;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;

#[Attribute]
class OpenApiBasicAuth implements OpenApiSecurityAttribute
{
    public function getType(): string
    {
        return SecurityScheme::TYPE_HTTP;
    }

    public function scheme(): ?string
    {
        return 'basic';
    }

    public function schemeName(): string
    {
        return 'BasicAuth';
    }
}
