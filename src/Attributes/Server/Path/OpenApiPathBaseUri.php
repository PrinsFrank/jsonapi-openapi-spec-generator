<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path;

use Attribute;

#[Attribute]
class OpenApiPathBaseUri implements OpenApiPathAttribute
{
    public const OBJECT_ID = 'base_uri';

    /** @var string[] */
    public array $enum;

    public function __construct(string ... $baseUris)
    {
        $this->enum = $baseUris;
    }

    public function description(): string
    {
        return 'The base uri';
    }

    public function objectId(): string
    {
        return self::OBJECT_ID;
    }
}
