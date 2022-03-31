<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path;

use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\OpenApiAttribute;

interface OpenApiPathAttribute extends OpenApiAttribute
{
    public function description(): string;

    public function objectId(): string;
}
