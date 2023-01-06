<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Url;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ServerVariable;

interface UrlBuilderContract
{
    /**
     * @param ServerVariable[] $variables
     */
    public function build(ServerDocumentation $serverDocumentation, array $variables, string $serverPattern, string $baseUri): ServerDocumentation;
}
