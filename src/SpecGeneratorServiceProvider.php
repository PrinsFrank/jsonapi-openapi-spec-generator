<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator;

use Illuminate\Support\ServiceProvider;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Components\ComponentsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\ExternalDocs\ExternalDocsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Info\InfoBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Paths\PathsBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Security\SecurityBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Servers\ServersBuilder;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Tags\TagsBuilder;

class SpecGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            SpecGenerator::class,
            static function () {
                return new SpecGenerator(
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
}
