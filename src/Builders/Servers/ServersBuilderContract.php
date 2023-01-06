<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use LaravelJsonApi\Core\Server\Server;

interface ServersBuilderContract
{
    /**
     * @return ServerDocumentation[]
     */
    public function build(Server $server): array;
}
