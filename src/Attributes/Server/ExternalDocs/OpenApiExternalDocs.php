<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\ExternalDocs;

use Attribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\OpenApiAttribute;

#[Attribute]
class OpenApiExternalDocs implements OpenApiAttribute
{
    public function __construct(public ?string $url = null, public ?string $description = null)
    {
    }
}
