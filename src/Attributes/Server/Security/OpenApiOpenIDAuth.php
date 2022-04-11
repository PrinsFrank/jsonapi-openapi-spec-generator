<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Security;

use Attribute;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;

#[Attribute]
class OpenApiOpenIDAuth implements OpenApiSecurityAttribute
{
    public function __construct(public string $openIdConnectUrl)
    {
    }

    public function getType(): string
    {
        return SecurityScheme::TYPE_OPEN_ID_CONNECT;
    }

    public function scheme(): ?string
    {
        return null;
    }

    public function schemeName(): string
    {
        return 'OpenID';
    }
}
