<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

class Attribute
{
    public static function methodHas(object|string $class, string $method, string $attributeFQN): bool
    {
        return self::has(new ReflectionMethod($class, $method), $attributeFQN);
    }

    /**
     * @template T
     * @param class-string<T> $attributeFQN
     * @return T|null
     */
    public static function methodGet(object|string $class, string $method, string $attributeFQN): ?OpenApiAttribute
    {
        return self::get(new ReflectionMethod($class, $method), $attributeFQN);
    }

    public static function classHas(object|string $class, string $attributeFQN): bool
    {
        return self::has(new ReflectionClass($class), $attributeFQN);
    }

    /**
     * @template T
     * @param class-string<T> $attributeFQN
     * @return T|null
     */
    public static function classGet(object|string $class, string $attributeFQN): ?OpenApiAttribute
    {
        return self::get(new ReflectionClass($class), $attributeFQN);
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

    /**
     * @template T
     * @param class-string<T> $attributeFQN
     * @return T|null
     */
    public static function get(ReflectionClass|ReflectionMethod $reflection, string $attributeFQN): ?OpenApiAttribute
    {
        foreach ($reflection->getAttributes() as $reflectionAttribute) {
            $attributeInstance = $reflectionAttribute->newInstance();

            if ($attributeInstance instanceof $attributeFQN && $attributeInstance instanceof OpenApiAttribute) {
                return $attributeInstance;
            }
        }

        return null;
    }

    /**
     * @return ReflectionAttribute[]
     */
    public static function allForClass(object|string $class): array
    {
        return self::all(new ReflectionClass($class));
    }

    /**
     * @return ReflectionAttribute[]
     */
    public static function allForMethod(object|string $class, string $method): array
    {
        return self::all(new ReflectionMethod($class, $method));
    }

    /**
     * @return ReflectionAttribute[]
     */
    public static function all(ReflectionClass|ReflectionMethod $reflection): array
    {
        return $reflection->getAttributes();
    }
}
