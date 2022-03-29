<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Components\ComponentsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\ExternalDocs\ExternalDocsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Info\InfoBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Paths\PathsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Security\SecurityBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Servers\ServersBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Tags\TagsBuilder;

class OpenApiSpecGeneratorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(
            OpenApiSpecGenerator::class,
            static function (Application $application) {
                return new OpenApiSpecGenerator(
                    $application,
                    new TagsBuilder(),
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

    public function provides(): array
    {
        return [
            OpenApiSpecGenerator::class
        ];
    }
}
