<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Routing\Route;
use LaravelJsonApi\Core\Server\Server;

class ResponsesBuilder
{
    private const APPLICATION_JSON_API = 'application/vnd.api+json';

    /** @return Response[] */
    public static function build(Server $server, Route $route): array
    {
        $serverName = $server->name();
        $type       = array_filter($server->schemas()->types(), static function ($type) use ($serverName, $route) {
                return str_starts_with($route->getAction('as') ?? '', $serverName . '.' . $type);
            })[0] ?? null;
        if ($type === null) {
            return [];
        }

        $responses   = [
            Response::ref('#/components/responses/400', '400')->statusCode(400),
            Response::ref('#/components/responses/401', '401')->statusCode(401),
            Response::ref('#/components/responses/401', '404')->statusCode(404),
        ];
        $responses[] = (new Response())->statusCode(200)
            ->content(
                (new MediaType())
                    ->mediaType(self::APPLICATION_JSON_API)
                    ->schema(
                        (new Schema())
                            ->type(Schema::TYPE_OBJECT)
                            ->required('data', 'jsonapi')
                            ->properties(
                                Schema::ref('#/components/jsonapi-version', 'jsonapi'),
                                Schema::array('data')->items(Schema::ref('#/components/schemas/' . $type)),
                            )
                    )
            );

        return $responses;
    }
}
