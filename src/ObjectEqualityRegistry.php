<?php
namespace Value;

final class ObjectEqualityRegistry
{
    private static $finalized = false;
    /**
     * @var ObjectEquality[] (Class names, not instances -- ObjectEquality is just here as a hint to PHPStorm)
     */
    private static $equalityClasses = [];
    private static $singleClassMatchCache = [];
    private static $dualClassMatchCache = [];

    public static function register(string $objectEqualityClass): void
    {
        \assert(
            \is_a($objectEqualityClass, ObjectEquality::class, true),
            "Invalid equality class $objectEqualityClass: class does not implement " . ObjectEquality::class
        );

        if (self::$finalized) {
            throw new \LogicException("Cannot register equality class $objectEqualityClass: the registry was already finalized.");
        }

        self::$equalityClasses[$objectEqualityClass] = $objectEqualityClass;
    }

    public static function finalize(): void
    {
        self::$finalized = true;
    }

    /**
     * @internal
     */
    public static function hash($value): ?int
    {
        self::$finalized = true;

        $equalityClass = self::getEqualityClassMatching(\get_class($value));
        return $equalityClass ? $equalityClass::hash($value) : null;
    }

    /**
     * @internal
     */
    public static function equal($value1, $value2): ?bool
    {
        self::$finalized = true;

        $value1Class = \get_class($value1);
        $value2Class = \get_class($value2);

        if ($value1Class === $value2Class) {
            $equalityClass = self::getEqualityClassMatching($value1Class);
        } else {
            $equalityClass = self::getEqualityClassMatchingBoth($value1Class, $value2Class);
        }

        return $equalityClass ? $equalityClass::equal($value1, $value2) : null;
    }

    /**
     * @return null|string|ObjectEquality Doesn't really return an instance -- this is just here as a hint to PHPStorm
     */
    private static function getEqualityClassMatching(string $valueClass): ?string
    {
        if (\array_key_exists($valueClass, self::$singleClassMatchCache)) {
            return self::$singleClassMatchCache[$valueClass];
        }

        foreach (self::$equalityClasses as $equalityClass) {
            if ($equalityClass::matches($valueClass)) {
                return self::$singleClassMatchCache[$valueClass] = $equalityClass;
            }
        }

        return self::$singleClassMatchCache[$valueClass] = null;
    }

    /**
     * @return null|string|ObjectEquality Doesn't really return an instance -- this is just here as a hint to PHPStorm
     */
    private static function getEqualityClassMatchingBoth(string $valueClass1, string $valueClass2): ?string
    {
        $cacheKey = $valueClass1 < $valueClass2
            ? "$valueClass1 $valueClass2"
            : "$valueClass2 $valueClass1";

        if (\array_key_exists($cacheKey, self::$dualClassMatchCache)) {
            return self::$dualClassMatchCache[$cacheKey];
        }

        foreach (self::$equalityClasses as $equalityClass) {
            if ($equalityClass::matches($valueClass1) && $equalityClass::matches($valueClass2)) {
                return self::$dualClassMatchCache[$cacheKey] = $equalityClass;
            }
        }

        return self::$dualClassMatchCache[$cacheKey] = null;
    }
}
