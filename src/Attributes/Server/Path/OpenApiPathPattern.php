<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path;

use Attribute;

#[Attribute]
class OpenApiPathPattern implements OpenApiPathAttribute
{
    public const OBJECT_ID = 'pattern';

    public function __construct(public string $pattern)
    {
    }

    public function description(): string
    {
        return 'The server pattern';
    }

    public function objectId(): string
    {
        return self::OBJECT_ID;
    }
}
