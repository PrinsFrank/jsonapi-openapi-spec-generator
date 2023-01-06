<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers\Url;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ServerVariable;
use PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server\Path\OpenApiPathBaseUri;

class UrlBuilder implements UrlBuilderContract
{
    /**
     * @param ServerVariable[] $variables
     */
    public function build(ServerDocumentation $serverDocumentation, array $variables, string $serverPattern, string $baseUri): ServerDocumentation
    {
        $variablesWithOptions = [];
        foreach ($variables as $variable) {
            if ($variable->enum === null || $variable->objectId === null) {
                continue;
            }

            if (count($variable->enum) > 1) {
                $variablesWithOptions[] = $variable;
                $newServerPattern       = preg_replace('/{(?<prefix>[^}]*)' . $variable->objectId . '(?<suffix>[^}]*)}/', '${1}{' . $variable->objectId . '}${2}', $serverPattern);
                if ($newServerPattern !== null) {
                    $serverPattern = $newServerPattern;
                }
                continue;
            }

            if (count($variable->enum) === 1) {
                $newServerPattern = preg_replace('/{(?<prefix>[^}]*)' . $variable->objectId . '(?<suffix>[^}]*)}/', '${1}' . array_values($variable->enum)[0] . '${2}', $serverPattern);
                if ($newServerPattern !== null) {
                    $serverPattern = $newServerPattern;
                }
            }
        }

        preg_match_all('/{[^}]*?([A-z]+)[^}]*?}/', $serverPattern, $remainingVariableDefinitions);
        foreach ($remainingVariableDefinitions[1] ?? [] as $remainingVariableDefinition) {
            $existingVariables = array_map(static function ($variable) {
                return $variable->objectId;
            }, $variables);
            if (in_array($remainingVariableDefinition, $existingVariables, true)) {
                continue;
            }

            if ($remainingVariableDefinition === OpenApiPathBaseUri::OBJECT_ID) {
                $newServerPattern = preg_replace('/{(?<prefix>[^}]*)' . OpenApiPathBaseUri::OBJECT_ID. '(?<suffix>[^}]*)}/', '${1}' . $baseUri . '${2}', $serverPattern);
                if ($newServerPattern !== null) {
                    $serverPattern = $newServerPattern;
                }
                continue;
            }

            $newServerPattern = preg_replace('/{[^}]*' . $remainingVariableDefinition . '[^}]*}/', '', $serverPattern);
            if ($newServerPattern !== null) {
                $serverPattern = $newServerPattern;
            }
        }

        return $serverDocumentation->variables(... $variablesWithOptions)->url($serverPattern);
    }
}
