<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\Response;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Attribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Controller\Method\OpenApiJWTResponse;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\Responses\ResponsesBuilder;

class ResponseBuilder
{
    /** @return Response[] */
    public static function build(Server $server, Router $router, UrlGenerator $urlGenerator): array
    {
        $content = MediaType::create()
            ->mediaType(ResponsesBuilder::APPLICATION_JSON_API)
            ->schema(Schema::object()->properties(Schema::ref('#/components/schemas/error')->objectId('error'), Schema::ref('#/components/schemas/jsonapi-version')->objectId('jsonapi-version')));

        $responses = [
            Response::badRequest('400')->statusCode(400)->content($content),
            Response::forbidden('401')->statusCode(401)->content($content),
            Response::notFound('404')->statusCode(404)->content($content),
            Response::unprocessableEntity('422')->statusCode(422)->content($content),
        ];

        /** @var array<Route> $routes */
        $routes = $router->getRoutes();
        foreach ($routes as $route) {
            if (str_starts_with($urlGenerator->to($route->uri()), $server->url()) && Attribute::methodHas($route->getController(), $route->getActionMethod(), OpenApiJWTResponse::class)) {
                $responses[] = Response::ok('jwt-token')
                    ->statusCode(200)
                    ->content(MediaType::create()
                            ->mediaType(ResponsesBuilder::APPLICATION_JSON_API)
                            ->schema(Schema::object()->properties(Schema::string('access_token'), Schema::string('token_type')->default('bearer'), Schema::integer('expires_in')->default(3600))));
            }
        }

        return $responses;
    }
}
