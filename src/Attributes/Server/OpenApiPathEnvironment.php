<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server;

use Attribute;

#[Attribute]
class OpenApiPathEnvironment implements OpenApiPathAttribute
{
    public const OBJECT_ID = 'environment';

    /** @var string[] */
    public array $enum;

    public function __construct(string ...$environments)
    {
        $this->enum = $environments;
    }

    public function description(): string
    {
        return 'The server domain';
    }

    public function objectId(): string
    {
        return self::OBJECT_ID;
    }
}
