<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Tests\Feature\_data\Servers;

use LaravelJsonApi\Core\Server\Server;

class EmptyServer extends Server
{
    protected function allSchemas(): array
    {
        return [];
    }
}
