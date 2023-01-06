<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Tests\Feature\_data\Schemas;

use LaravelJsonApi\Core\Schema\Schema;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Tests\Feature\_data\Models\PostModel;
use LaravelJsonApi\Eloquent\Filters\Where;

class PostSchema extends Schema
{
    public static string $model = PostModel::class;

    public function fields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make('author')->type('users')->readOnly(),
            HasMany::make('comments')->readOnly(),
            Str::make('content'),
            DateTime::make('createdAt')->sortable()->readOnly(),
            Str::make('slug'),
            Str::make('synopsis'),
            BelongsToMany::make('tags'),
            Str::make('title')->sortable(),
            DateTime::make('updatedAt')->sortable()->readOnly(),
        ];
    }

    public function filters(): iterable
    {
	    return [
		    Where::make('id')->singular(),
		    Where::make('author_id'),
	    ];
    }
}
