<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Response;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\Responses\ResponsesBuilder;

class ResponseBuilder
{
    /** @return Response[] */
    public static function build(): array
    {
        $content = MediaType::create()
            ->mediaType(ResponsesBuilder::APPLICATION_JSON_API)
            ->schema(Schema::object()->properties(Schema::ref('#/components/schemas/error')->objectId('error'), Schema::ref('#/components/schemas/jsonapi-version')->objectId('jsonapi-version')));

        return [
            Response::badRequest('400')->statusCode(400)->content($content),
            Response::forbidden('401')->statusCode(401)->content($content),
            Response::notFound('404')->statusCode(404)->content($content),
            Response::unprocessableEntity('422')->statusCode(422)->content($content),
        ];
    }
}
