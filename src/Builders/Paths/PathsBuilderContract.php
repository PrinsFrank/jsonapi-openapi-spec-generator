<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use LaravelJsonApi\Core\Server\Server;

interface PathsBuilderContract
{
    /** @return PathItem[] */
    public function build(Server $server): array;
}
