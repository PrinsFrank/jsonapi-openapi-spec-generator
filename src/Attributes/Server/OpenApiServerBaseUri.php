<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server;

class OpenApiServerBaseUri implements OpenApiServerAttribute
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
