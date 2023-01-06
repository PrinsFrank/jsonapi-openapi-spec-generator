<?php

namespace Tests\Unit\Builders\Path;

use PHPUnit\Framework\TestCase;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\RouteParams\RouteParamsBuilder;
use LaravelJsonApi\Core\Server\Server;
use Illuminate\Routing\Route;
use LaravelJsonApi\Laravel\Routing\Route as JsonApiRoute;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use LaravelJsonApi\Contracts\Schema\Container as SchemaContainerContract;
use LaravelJsonApi\Core\Schema\Schema as JsonApiSchema;
use LaravelJsonApi\Eloquent\Filters\Where;

/**
 * @coversDefaultClass \PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\RouteParams\RouteParamsBuilder
 */
class RouteParamsBuilderTest extends TestCase
{
    /**
     * @covers ::build
     */
    public function testBuildNone(): void
    {
        $server = $this->createMock(Server::class);
        $route = $this->createMock(Route::class);
        $route->expects(self::once())->method('uri')->willReturn('/foo');

        $parameters = RouteParamsBuilder::build($server, $route);
        self::assertEquals([], $parameters);
    }

    /**
     * @covers ::build
     */
    public function testBuildUrlParameter(): void
    {
        $server = $this->createMock(Server::class);
        $route = $this->createMock(Route::class);
        $route->expects(self::once())->method('uri')->willReturn('/foo/{bar}');

        $parameters = RouteParamsBuilder::build($server, $route);
        self::assertEquals(
            [
                $routeParams[] = (new Parameter())
                    ->in('path')
                    ->name('bar')
                    ->schema(Schema::integer())
                    ->required()
            ], 
            $parameters
        );
    }

    /**
     * @covers ::build
     */
    public function testBuildFilters(): void
    {
        $filter = $this->createMock(Where::class);
        $filter->expects(self::once())->method('key')->willReturn('foo');
        $filter->expects(self::once())->method('isSingular')->willReturn(true);

        $schema = $this->createMock(JsonApiSchema::class);
        $schema->expects(self::once())->method('filters')->willReturn([$filter]);

        $schemaContainer = $this->createMock(SchemaContainerContract::class);
        $schemaContainer->expects(self::once())->method('schemaFor')->with('posts')->willReturn($schema);

        $server = $this->createMock(Server::class);
        $server->expects(self::once())->method('schemas')->willReturn($schemaContainer);
        $route = $this->createMock(Route::class);
        $route->expects(self::once())->method('uri')->willReturn('/foo');
        $route->expects(self::once())->method('getName')->willReturn('index');
        $route->defaults['resource_type'] = 'posts';

        $parameters = RouteParamsBuilder::build($server, $route);
        self::assertEquals(
            [
                $routeParams[] = (new Parameter())
                    ->in('query')
                    ->name('filter[foo]')
                    ->schema(Schema::string())
            ],
            $parameters
        );
    }
}
