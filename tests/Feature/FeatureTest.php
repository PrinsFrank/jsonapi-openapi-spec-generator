<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Tests\Feature;

use Generator;
use LaravelJsonApi\Laravel\ServiceProvider;
use Orchestra\Testbench\TestCase;
use PrinsFrank\JsonapiOpenapiSpecGenerator\OpenApiSpecGenerator;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Tests\Feature\_data\Servers\EmptyServer;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Tests\Feature\_data\Servers\SimpleServer;

/**
 * @coversNothing
 */
class FeatureTest extends TestCase
{
    private const SERVERS = [
        'empty_server' => EmptyServer::class,
        'simple_server' => SimpleServer::class,
    ];

    /**
     * @dataProvider scenarios
     */
    public function testScenarios(string $serverName): void
    {
        $specGenerator = $this->app->make(OpenApiSpecGenerator::class);

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
        foreach (self::SERVERS as $serverName => $serverFQN) {
            yield [$serverName];
        }
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('jsonapi.servers', self::SERVERS);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
