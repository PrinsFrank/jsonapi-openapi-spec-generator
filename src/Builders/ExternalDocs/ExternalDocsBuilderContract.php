<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\ExternalDocs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\ExternalDocs;
use LaravelJsonApi\Core\Server\Server;

interface ExternalDocsBuilderContract
{
    public function build(Server $server): ?ExternalDocs;
}
