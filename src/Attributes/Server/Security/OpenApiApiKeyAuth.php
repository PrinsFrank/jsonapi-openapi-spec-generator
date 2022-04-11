<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Security;

use Attribute;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\InvalidAttributeArgument;

#[Attribute]
class OpenApiApiKeyAuth implements OpenApiSecurityAttribute
{
    public const ALLOWED_IN_VALUES = [
        SecurityScheme::IN_COOKIE,
        SecurityScheme::IN_QUERY,
        SecurityScheme::IN_HEADER,
    ];

    /**
     * @throws InvalidAttributeArgument
     */
    public function __construct(public string $in, public string $name)
    {
        if (in_array($in, self::ALLOWED_IN_VALUES, true) === false) {
            throw new InvalidAttributeArgument('Value not in allowed values: "' . implode(',', self::ALLOWED_IN_VALUES) . '"');
        }
    }

    public function getType(): string
    {
        return SecurityScheme::TYPE_API_KEY;
    }

    public function scheme(): ?string
    {
        return null;
    }

    public function schemeName(): string
    {
        return 'ApiKeyAuth';
    }
}
