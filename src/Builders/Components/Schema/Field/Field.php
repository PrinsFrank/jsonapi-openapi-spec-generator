<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Schema\Field;

use LaravelJsonApi\Contracts\Schema\Field as SchemaField;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\NotImplementedException;

class Field
{
    /**
     * @throws NotImplementedException
     */
    public static function getType(SchemaField $field): string
    {
        return match(get_class($field)) {
            Str::class, DateTime::class => 'string',
            Boolean::class => 'boolean',
            ID::class, Number::class => 'number',
            BelongsTo::class => 'object',
            BelongsToMany::class => 'array',
            default => throw new NotImplementedException('Retrieving type for ' . get_class($field) . ' failed')
        };
    }
}
