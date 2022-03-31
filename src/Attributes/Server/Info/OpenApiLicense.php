<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Info;

use Attribute;

#[Attribute]
class OpenApiLicense implements OpenApiInfoAttribute
{
    public function __construct(public ?string $name, public ?string $url) { }
}
