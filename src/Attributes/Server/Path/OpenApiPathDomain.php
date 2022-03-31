<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path;

use Attribute;

#[Attribute]
class OpenApiPathDomain implements OpenApiPathAttribute
{
    public const OBJECT_ID = 'domain';

    /** @var string[] */
    public array $enum;

    public function __construct(string ... $domains)
    {
        $this->enum = $domains;
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
