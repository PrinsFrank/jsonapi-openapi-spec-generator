<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Security;

use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Attribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Security\OpenApiSecurityAttribute;

class SecurityBuilder implements SecurityBuilderContract
{
    /** @return SecurityRequirement[] */
    public function build(Server $server): array
    {
        $securityAttribute = Attribute::classGet($server, OpenApiSecurityAttribute::class);
        if ($securityAttribute !== null) {
            return [
                SecurityRequirement::create()
                    ->securityScheme(
                        SecurityScheme::ref('#/components/securitySchemes/' . $securityAttribute->schemeName(), $securityAttribute->schemeName())->name($securityAttribute->schemeName())->type($securityAttribute->getType())
                    )
            ];
        }

        return [];
    }
}
