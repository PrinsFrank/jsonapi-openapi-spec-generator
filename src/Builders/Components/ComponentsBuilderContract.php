<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use LaravelJsonApi\Core\Server\Server;

interface ComponentsBuilderContract
{
    public function build(Server $server): ?Components;
}
