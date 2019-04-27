<?php
namespace Value;

interface ObjectEquality
{
    public static function matches(string $class): bool;

    public static function hash($value): int;

    public static function equal($value1, $value2): bool;
}
