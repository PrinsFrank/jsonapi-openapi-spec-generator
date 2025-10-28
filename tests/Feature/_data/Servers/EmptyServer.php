<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Tests\Feature\_data\Servers;

use LaravelJsonApi\Contracts\Schema\Schema;
use LaravelJsonApi\Core\Server\Server;

class EmptyServer extends Server
{
    protected string $baseUri = '/api';

    /**
     * @return array<class-string<Schema>>
     */
    protected function allSchemas(): array
    {
        return [];
    }
}
