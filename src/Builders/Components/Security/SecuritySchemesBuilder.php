<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Security;

use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Attribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Security\OpenApiSecurityAttribute;

class SecuritySchemesBuilder
{
    /** @return SecurityScheme[] */
    public static function build(Server $server): array
    {
        $securitySchemes   = [];
        $securityAttribute = Attribute::classGet($server, OpenApiSecurityAttribute::class);
        if ($securityAttribute !== null) {
            $securitySchemes[] = SecurityScheme::create($securityAttribute->schemeName())->type($securityAttribute->getType())->scheme($securityAttribute->scheme());
        }

        return $securitySchemes;
    }
}
