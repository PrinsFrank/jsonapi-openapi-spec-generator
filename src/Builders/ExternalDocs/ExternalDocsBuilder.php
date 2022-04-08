<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\ExternalDocs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\ExternalDocs;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Attribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\ExternalDocs\OpenApiExternalDocs;

class ExternalDocsBuilder
{
    public function build(Server $server): ?ExternalDocs
    {
        foreach (Attribute::allForClass($server) as $reflectionAttribute) {
            $attribute = $reflectionAttribute->newInstance();
            if ($attribute instanceof OpenApiExternalDocs === false || ($attribute->url === null && $attribute->description === null)) {
                continue;
            }

            return (new ExternalDocs())
                ->url($attribute->url)
                ->description($attribute->description);
        }

        return null;
    }
}
