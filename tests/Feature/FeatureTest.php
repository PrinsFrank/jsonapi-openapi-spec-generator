<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Tests\Feature;

use Generator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Controller;
use LaravelJsonApi\Laravel\Routing\Registrar;
use LaravelJsonApi\Laravel\ServiceProvider;
use Orchestra\Testbench\TestCase;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Exception\JsonapiOpenapiSpecGeneratorException;
use PrinsFrank\JsonapiOpenapiSpecGenerator\OpenApiSpecGenerator;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Tests\Feature\_data\Controllers\PostController;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Tests\Feature\_data\Servers\EmptyServer;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Tests\Feature\_data\Servers\SimpleServer;
use RuntimeException;

/**
 * @coversNothing
 */
class FeatureTest extends TestCase
{
    private const SERVER_NAME_EMPTY = 'empty_server';

    private const SERVER_NAME_SIMPLE = 'simple_server';

    private const SERVERS = [
        self::SERVER_NAME_EMPTY  => EmptyServer::class,
        self::SERVER_NAME_SIMPLE => SimpleServer::class,
    ];

    /**
     * @dataProvider scenarios
     * @param self::SERVER_NAME_* $serverName
     * @param array<Controller> $controllers
     * @throws \JsonException
     * @throws JsonapiOpenapiSpecGeneratorException
     */
    public function testScenarios(string $serverName, array $controllers): void
    {
        /** @var OpenApiSpecGenerator $specGenerator */
        $specGenerator = $this->app?->make(OpenApiSpecGenerator::class) ?? throw new RuntimeException('Running this test without a booted application is not possible');

        /** @var Registrar $registrar */
        $registrar = $this->app->make(Registrar::class);
        $registrar->server($serverName)
            ->prefix('api')
            ->resources(static function ($server) use ($controllers) {
                foreach ($controllers as $resourceName => $controllerFQN) {
                    $server->resource($resourceName, $controllerFQN);
                }
            });

        $actualPath   = __DIR__ . '/actual/' . $serverName . '.json';
        $expectedPath = __DIR__ . '/expected/' . $serverName . '.json';

        $output = json_encode($specGenerator->generate($serverName), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        file_put_contents($actualPath, $output);
        if (file_exists($expectedPath) === false) {
            file_put_contents($expectedPath, $output);
        }

        static::assertFileEquals($expectedPath, $actualPath);
    }

    public function scenarios(): Generator
    {
        yield [self::SERVER_NAME_EMPTY, []];
        yield [self::SERVER_NAME_SIMPLE, ['posts' => PostController::class]];
    }

    /**
     * @inheritDoc
     */
    protected function defineEnvironment($application): void
    {
        $application->make(Repository::class)->set('jsonapi.servers', self::SERVERS);
    }

    /**
     * @inheritDoc
     */
    protected function getPackageProviders($application): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
