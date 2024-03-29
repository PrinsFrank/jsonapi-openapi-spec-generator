<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Info;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use LaravelJsonApi\Core\Server\Server;

interface InfoBuilderContract
{
    public function build(Server $server): ?Info;
}
