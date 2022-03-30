<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server;

use Attribute;

#[Attribute]
class OpenApiServerProtocol implements OpenApiServerAttribute
{
    /** @var string[] */
    public array $protocols;

    public function __construct(string ...$protocols)
    {
        $this->protocols = $protocols;
    }
}
