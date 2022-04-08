<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes;

use ReflectionClass;
use ReflectionMethod;

class Attribute
{
    public static function methodHas(object|string $class, string $method, string $attributeFQN): bool
    {
        return self::has(new ReflectionMethod($class, $method), $attributeFQN);
    }

    public static function classHas(object|string $class, string $attributeFQN): bool
    {
        return self::has(new ReflectionClass($class), $attributeFQN);
    }

    public static function has(ReflectionClass|ReflectionMethod $reflection, string $attributeFQN): bool
    {
        foreach ($reflection->getAttributes() as $reflectionAttribute) {
            if ($reflectionAttribute->newInstance() instanceof $attributeFQN) {
                return true;
            }
        }

        return false;
    }
}
