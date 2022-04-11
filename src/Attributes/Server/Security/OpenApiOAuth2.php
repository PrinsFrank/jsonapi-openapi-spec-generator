<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Security;

use Attribute;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;

#[Attribute]
class OpenApiOAuth2 implements OpenApiSecurityAttribute
{
    public function getType(): string
    {
        return SecurityScheme::TYPE_OAUTH2;
    }

    public function scheme(): ?string
    {
        return null;
    }

    public function schemeName(): string
    {
        return 'Oauth2';
    }
}
