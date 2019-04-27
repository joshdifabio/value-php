<?php
namespace Value;

/**
 * This class provides the ability for package vendors to add first class @see equals() and @see hash() support to
 * legacy value object classes without implementing @see Value directly.
 */
interface ObjectEquality
{
    /**
     * Indicates whether this @see ObjectEquality class implements equality functions for the specified class.
     */
    public static function matches(string $class): bool;

    /**
     * @see Value::equals()
     */
    public static function equal($value1, $value2): bool;

    /**
     * @see Value::hashCode()
     */
    public static function hash($value): int;
}
