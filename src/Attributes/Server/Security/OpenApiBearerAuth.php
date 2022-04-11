<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Security;

use Attribute;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;

#[Attribute]
class OpenApiBearerAuth implements OpenApiSecurityAttribute
{
    public function __construct(public ?string $format = null)
    {
    }

    public function getType(): string
    {
        return SecurityScheme::TYPE_HTTP;
    }

    public function scheme(): ?string
    {
        return 'bearer';
    }

    public function schemeName(): string
    {
        return 'BearerAuth';
    }
}
