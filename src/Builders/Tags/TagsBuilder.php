<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Tags;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Tag;
use LaravelJsonApi\Core\Server\Server;

class TagsBuilder
{
    /** @return Tag[] */
    public function build(Server $server): array
    {
        return [];
    }
}
