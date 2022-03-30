<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use LaravelJsonApi\Core\Server\Server;

class PathsBuilder
{
    /** @return PathItem[] */
    public function build(Server $server)
    {
        return [];
    }
}
