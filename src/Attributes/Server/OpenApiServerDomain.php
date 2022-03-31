<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server;

use Attribute;

#[Attribute]
class OpenApiServerDomain implements OpenApiServerAttribute
{
    /** @var string[] */
    public array $domains;

    public function __construct(string ... $domains)
    {
        $this->domains = $domains;
    }
}
