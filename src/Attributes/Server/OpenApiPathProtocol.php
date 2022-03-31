<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server;

use Attribute;

#[Attribute]
class OpenApiPathProtocol implements OpenApiPathAttribute
{
    public const OBJECT_ID = 'protocol';

    /** @var string[] */
    public array $enum;

    public function __construct(string ...$protocols)
    {
        $this->enum = $protocols;
    }

    public function description(): string
    {
        return 'The protocol';
    }

    public function objectId(): string
    {
        return self::OBJECT_ID;
    }
}
