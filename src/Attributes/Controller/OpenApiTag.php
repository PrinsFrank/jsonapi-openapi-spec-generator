<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Controller;

use Attribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\OpenApiAttribute;

#[Attribute]
class OpenApiTag implements OpenApiAttribute
{
    public function __construct(public string $tagName)
    {
    }
}
