<?php
declare(strict_types=1);

namespace Tests\Unit\Builders\Path;

use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use PHPUnit\Framework\TestCase;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\RouteParams\RouteParamsBuilder;
use LaravelJsonApi\Core\Server\Server;
use Illuminate\Routing\Route;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use LaravelJsonApi\Contracts\Schema\Container as SchemaContainerContract;
use LaravelJsonApi\Core\Schema\Schema as JsonApiSchema;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Fields\Relations\Relation;

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
        $route = $this->createMock(Route::class);
        $route->expects(self::once())->method('uri')->willReturn('/foo');

        self::assertSame(
            [],
            (new RouteParamsBuilder())->build($this->createMock(Server::class), $route)
        );
    }

    /**
     * @covers ::build
     */
    public function testBuildUrlParameter(): void
    {
        $route = $this->createMock(Route::class);
        $route->expects(self::once())->method('uri')->willReturn('/foo/{bar}');

        self::assertEquals(
            [
                (new Parameter())
                    ->in('path')
                    ->name('bar')
                    ->schema(Schema::integer())
                    ->required()
            ],
            (new RouteParamsBuilder())->build($this->createMock(Server::class), $route)
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

        self::assertEquals(
            [
                (new Parameter())
                    ->in('query')
                    ->name('filter[foo]')
                    ->schema((new AnyOf())->schemas(Schema::string(), Schema::integer(), Schema::boolean()))
            ],
            (new RouteParamsBuilder())->build($server, $route)
        );
    }

    /**
     * @covers ::build
     */
    public function testBuildRelation(): void
    {
        $relation = $this->createMock(Relation::class);
        $relation->expects(self::once())->method('name')->willReturn('tags');

        $schema = $this->createMock(JsonApiSchema::class);
        $schema->expects(self::once())->method('relationships')->willReturn([$relation]);

        $schemaContainer = $this->createMock(SchemaContainerContract::class);
        $schemaContainer->expects(self::once())->method('schemaFor')->with('posts')->willReturn($schema);

        $server = $this->createMock(Server::class);
        $server->expects(self::once())->method('schemas')->willReturn($schemaContainer);

        $route = $this->createMock(Route::class);
        $route->expects(self::once())->method('uri')->willReturn('/foo');
        $route->expects(self::once())->method('getName')->willReturn('index');
        $route->defaults['resource_type'] = 'posts';

        self::assertEquals(
            [
                (new Parameter())
                    ->in('query')
                    ->name('include')
                    ->schema(Schema::string()->enum('tags'))
            ],
            (new RouteParamsBuilder())->build($server, $route)
        );
    }
}
