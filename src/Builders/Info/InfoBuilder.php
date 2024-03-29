<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Info;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Contact;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use GoldSpecDigital\ObjectOrientedOAS\Objects\License;
use LaravelJsonApi\Core\Server\Server;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Attribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Info\OpenApiContact;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Info\OpenApiDescription;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Info\OpenApiInfoAttribute;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Info\OpenApiLicense;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Info\OpenApiTermsOfService;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Info\OpenApiTitle;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Info\OpenApiVersion;

class InfoBuilder implements InfoBuilderContract
{
    public function build(Server $server): ?Info
    {
        $info = (new Info())->title('API')->version('1.0.0');
        foreach (Attribute::allForClass($server) as $reflectionAttribute) {
            $attribute = $reflectionAttribute->newInstance();
            if ($attribute instanceof OpenApiInfoAttribute === false) {
                continue;
            }

            $info = match (get_class($attribute)) {
                /** @phpstan-ignore-next-line */
                OpenApiContact::class        => $info->contact((new Contact())->url($attribute->url)->name($attribute->name)->email($attribute->email)),
                /** @phpstan-ignore-next-line */
                OpenApiDescription::class    => $info->description($attribute->description),
                /** @phpstan-ignore-next-line */
                OpenApiLicense::class        => $info->license((new License())->name($attribute->name)->url($attribute->url)),
                /** @phpstan-ignore-next-line */
                OpenApiTermsOfService::class => $info->termsOfService($attribute->termsOfService),
                /** @phpstan-ignore-next-line */
                OpenApiTitle::class          => $info->title($attribute->title),
                /** @phpstan-ignore-next-line */
                OpenApiVersion::class        => $info->version($attribute->version),
                default                      => $info,
            };
        }

        return $info;
    }
}
