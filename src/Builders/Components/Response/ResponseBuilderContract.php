<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Response;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use LaravelJsonApi\Core\Server\Server;

interface ResponseBuilderContract
{
    /** @return Response[] */
    public function build(Server $server): array;
}
