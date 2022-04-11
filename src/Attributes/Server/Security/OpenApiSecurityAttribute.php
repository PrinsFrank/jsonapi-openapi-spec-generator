<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Security;

use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\OpenApiAttribute;

interface OpenApiSecurityAttribute extends OpenApiAttribute
{
    public function schemeName(): string;

    public function getType(): string;

    public function scheme(): ?string;
}
