<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Schema;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use LaravelJsonApi\Core\Server\Server;

interface SchemaBuilderContract
{
    /** @return SchemaContract[] */
    public function build(Server $server): array;
}
