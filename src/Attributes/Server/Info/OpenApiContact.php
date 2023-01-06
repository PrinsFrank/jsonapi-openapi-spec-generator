<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Info;

use Attribute;

#[Attribute]
class OpenApiContact implements OpenApiInfoAttribute
{
    public function __construct(public ?string $name = null, public ?string $url = null, public ?string $email = null)
    {
    }
}
