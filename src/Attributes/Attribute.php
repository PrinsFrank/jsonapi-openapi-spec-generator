<?php

declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

class Attribute
{
    /**
     * @param class-string<OpenApiAttribute> $attributeFQN
     */
    public static function methodHas(object|string $class, string $method, string $attributeFQN): bool
    {
        return self::has(new ReflectionMethod($class, $method), $attributeFQN);
    }

    /**
     * @template T of OpenApiAttribute
     * @param class-string<T> $attributeFQN
     * @return T|null
     */
    public static function methodGet(object|string $class, string $method, string $attributeFQN): ?OpenApiAttribute
    {
        return self::get(new ReflectionMethod($class, $method), $attributeFQN);
    }

    /**
     * @template T of object
     * @param class-string<T>|T $class
     * @param class-string<OpenApiAttribute> $attributeFQN
     */
    public static function classHas(object|string $class, string $attributeFQN): bool
    {
        return self::has(new ReflectionClass($class), $attributeFQN);
    }

    /**
     * @template T of object
     * @template U of OpenApiAttribute
     * @param class-string<T>|T $class
     * @param class-string<U> $attributeFQN
     * @return U|null
     */
    public static function classGet(object|string $class, string $attributeFQN): ?OpenApiAttribute
    {
        return self::get(new ReflectionClass($class), $attributeFQN);
    }

    /**
     * @param ReflectionClass<object>|ReflectionMethod $reflection\
     * @param class-string<OpenApiAttribute> $attributeFQN
     */
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
     * @template T of OpenApiAttribute
     * @param ReflectionClass<object>|ReflectionMethod $reflection
     * @param class-string<T> $attributeFQN
     * @return T|null
     */
    public static function get(ReflectionClass|ReflectionMethod $reflection, string $attributeFQN): ?OpenApiAttribute
    {
        foreach ($reflection->getAttributes() as $reflectionAttribute) {
            $attributeInstance = $reflectionAttribute->newInstance();

            if ($attributeInstance instanceof $attributeFQN) {
                return $attributeInstance;
            }
        }

        return null;
    }

    /**
     * @param object|class-string<object> $class
     * @return ReflectionAttribute<object>[]
     */
    public static function allForClass(object|string $class): array
    {
        return self::all(new ReflectionClass($class));
    }

    /**
     * @return ReflectionAttribute<object>[]
     */
    public static function allForMethod(object|string $class, string $method): array
    {
        return self::all(new ReflectionMethod($class, $method));
    }

    /**
     * @param ReflectionClass<object>|ReflectionMethod $reflection
     * @return ReflectionAttribute<object>[]
     */
    public static function all(ReflectionClass|ReflectionMethod $reflection): array
    {
        return $reflection->getAttributes();
    }
}
