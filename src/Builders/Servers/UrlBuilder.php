<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Builders\Servers;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as ServerDocumentation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ServerVariable;

class UrlBuilder
{
    /**
     * @param ServerVariable[] $variables
     */
    public static function build(ServerDocumentation $serverDocumentation, array $variables, string $serverPattern): ServerDocumentation
    {
        $variablesWithOptions = [];
        foreach ($variables as $variable) {
            if (count($variable->enum) > 1) {
                $variablesWithOptions[] = $variable;
                $serverPattern = preg_replace('/{(?<prefix>[^}]*)' . $variable->objectId . '(?<suffix>[^}]*)}/', '${1}{' . $variable->objectId . '}${2}', $serverPattern);

                continue;
            }

            if (count($variable->enum) === 1) {
                $serverPattern = preg_replace('/{(?<prefix>[^}]*)' . $variable->objectId . '(?<suffix>[^}]*)}/', '${1}' . array_values($variable->enum)[0] . '${2}', $serverPattern);
            }
        }

        preg_match_all('/{[^}]*?([A-z]+)[^}]*?}/', $serverPattern, $remainingVariableDefinitions);
        foreach ($remainingVariableDefinitions[1] ?? [] as $remainingVariableDefinition) {
            $existingVariables = array_map(static function ($variable) {return $variable->objectId;}, $variables);
            if (in_array($remainingVariableDefinition, $existingVariables, true)) {
                continue;
            }

            $serverPattern = preg_replace('/{[^}]*' . $remainingVariableDefinition . '[^}]*}/', '', $serverPattern);
        }

        return $serverDocumentation->variables(... $variablesWithOptions)->url($serverPattern);
    }
}
