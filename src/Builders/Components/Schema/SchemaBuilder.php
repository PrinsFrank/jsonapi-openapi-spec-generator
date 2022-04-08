<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Schema;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use LaravelJsonApi\Contracts\Schema\Field as SchemaField;
use LaravelJsonApi\Core\Server\Server;
use LaravelJsonApi\Eloquent\Schema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema as OpenApiSchema;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Schema\Field\Field;

class SchemaBuilder
{
    /** @return SchemaContract[] */
    public static function build(Server $server): array
    {
        $schemaDocs = [];
        foreach ($server->schemas()->types() as $schemaType) {
            /** @var Schema $schemaForType */
            $schemaForType = $server->schemas()->schemaFor($schemaType);
            $properties = [];
            foreach ($schemaForType->fields() as $field) {
                /** @var SchemaField $field */
                $properties[] = OpenApiSchema::create($field->name())->type(Field::getType($field));
            }

            $schemaDocs[] = OpenApiSchema::object($schemaType)
                ->properties(... $properties);
        }

        return $schemaDocs;
    }
}
