<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Security;

use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement;
use LaravelJsonApi\Core\Server\Server;

interface SecurityBuilderContract
{
    /** @return SecurityRequirement[] */
    public function build(Server $server): array;
}
