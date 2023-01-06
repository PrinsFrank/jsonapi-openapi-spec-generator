<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\SecuritySchemes;

use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;
use LaravelJsonApi\Core\Server\Server;

interface SecuritySchemesBuilderContract
{
    /** @return SecurityScheme[] */
    public function build(Server $server): array;
}
