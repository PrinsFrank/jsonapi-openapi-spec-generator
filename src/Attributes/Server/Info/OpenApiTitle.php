<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Info;

use Attribute;

#[Attribute]
class OpenApiTitle implements OpenApiInfoAttribute
{
    public function __construct(public ?string $title)
    {
    }
}
