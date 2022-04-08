<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Schema\SchemaBuilder;

class ComponentsBuilder
{
    public function build(Server $server): ?Components
    {
        return (new Components())
            ->schemas(... SchemaBuilder::build($server));
    }
}
