<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server;

use Attribute;

#[Attribute]
class OpenApiServerEnvironment implements OpenApiServerAttribute
{
    /** @var string[] */
    public array $environments;

    public function __construct(string ...$environments)
    {
        $this->environments = $environments;
    }
}
