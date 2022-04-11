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
        $schemaDocs = [
            OpenApiSchema::object('jsonapi-version')->title('JSON:API version')->properties(OpenApiSchema::string('version')->title('version')->example('1.0')),
            OpenApiSchema::array('error')->required('title', 'status')->title('Error')->items(OpenApiSchema::object()->properties(OpenApiSchema::string('detail'), OpenApiSchema::string('status'), OpenApiSchema::string('title'), OpenApiSchema::object('source')->properties(OpenApiSchema::string('pointer'))))
        ];

        foreach ($server->schemas()->types() as $schemaType) {
            /** @var Schema $schemaForType */
            $schemaForType = $server->schemas()->schemaFor($schemaType);
            $properties = [];
            foreach ($schemaForType->fields() as $field) {
                /** @var SchemaField $field */
                $properties[] = OpenApiSchema::create($field->name())->type(Field::getType($field));
            }

            $schemaDocs[] = OpenApiSchema::object($schemaType)
                ->required('type', 'id', 'attributes')
                ->properties(
                    OpenApiSchema::string('type')->title('type')->default($schemaType),
                    OpenApiSchema::string('id')->example('1'),
                    OpenApiSchema::object('attributes')->properties(... $properties),
                    OpenApiSchema::object('relationships')
                );
        }

        return $schemaDocs;
    }
}
