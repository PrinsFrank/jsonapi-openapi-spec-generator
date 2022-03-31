<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server;

use Attribute;

#[Attribute]
class OpenApiServerPortNumber implements OpenApiServerAttribute
{
    public const OBJECT_ID = 'port_number';

    /** @var int[] */
    public array $enum;

    public function __construct(int ... $portNumbers)
    {
        $this->enum = $portNumbers;
    }

    public function description(): string
    {
        return 'The port number';
    }

    public function objectId(): string
    {
        return self::OBJECT_ID;
    }
}
