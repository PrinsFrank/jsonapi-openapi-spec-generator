<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Components\ComponentsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\ExternalDocs\ExternalDocsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Info\InfoBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Paths\PathsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Security\SecurityBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\ServersBuilder;

class OpenApiSpecGeneratorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(
            OpenApiSpecGenerator::class,
            static function (Application $application) {
                return new OpenApiSpecGenerator(
                    $application,
                    new ExternalDocsBuilder(),
                    new InfoBuilder(),
                    new ServersBuilder(),
                    new SecurityBuilder(),
                    new PathsBuilder(),
                    new ComponentsBuilder()
                );
            }
        );
    }

    /**
     * @return array<string|class-string>
     */
    public function provides(): array
    {
        return [
            OpenApiSpecGenerator::class
        ];
    }
}
