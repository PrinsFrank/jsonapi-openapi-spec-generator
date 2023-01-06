<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Routing\Route;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Attribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Controller\Method\OpenApiJWTResponse;

class ResponsesBuilder implements ResponsesBuilderContract
{
    public const APPLICATION_JSON_API = 'application/vnd.api+json';

    /** @return Response[] */
    public function build(Server $server, Route $route): array
    {
        $baseResponses = [
            Response::ref('#/components/responses/400', '400')->statusCode(400),
            Response::ref('#/components/responses/401', '401')->statusCode(401),
            Response::ref('#/components/responses/401', '404')->statusCode(404),
        ];

        if (Attribute::methodHas($route->getController(), $route->getActionMethod(), OpenApiJWTResponse::class)) {
            return array_merge($baseResponses, [Response::ref('#/components/responses/jwt-token', '200')->statusCode(200)]);
        }

        $serverName = $server->name();
        $type       = array_values(array_filter($server->schemas()->types(), static function ($type) use ($serverName, $route) {
            return str_starts_with($route->getAction('as') ?? '', $serverName . '.' . $type);
        }))[0] ?? null;
        if ($type === null) {
            return $baseResponses;
        }

        return array_merge($baseResponses, [
            Response::ok()
                ->statusCode(200)
                ->description(ucfirst($type))
                ->content(
                    (new MediaType())
                        ->mediaType(self::APPLICATION_JSON_API)
                        ->schema(
                            (new Schema())
                                ->type(Schema::TYPE_OBJECT)
                                ->required('data', 'jsonapi')
                                ->properties(
                                    Schema::ref('#/components/schemas/jsonapi-version', 'jsonapi'),
                                    Schema::array('data')->items(Schema::ref('#/components/schemas/' . $type)),
                                )
                        )
                )
        ]);
    }
}
