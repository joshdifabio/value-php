<?php
/**
 * Return types are omitted from function declarations as a micro-optimization as this code is extremely hot
 */

namespace Value
{
    use function Value\IntMath\add;

    /**
     * @return bool
     */
    function equal($value1, $value2)
    {
        switch (\gettype($value1)) {
            case 'object':
                if ($value1 === $value2) {
                    return true;
                }
                if ($value1 instanceof Value) {
                    return $value1->equals($value2);
                }
                if (!\is_object($value2)) {
                    return false;
                }
                return ObjectEqualityRegistry::equal($value1, $value2) ?? false;

            case 'array':
                if (!\is_array($value2)) {
                    return false;
                }
                if (\count($value1) !== \count($value2)) {
                    return false;
                }
                if (\array_keys($value1) !== \array_keys($value2)) {
                    return false;
                }
                foreach ($value1 as $key => $value) {
                    if (!equal($value, $value2[$key])) {
                        return false;
                    }
                }
                return true;

            default:
                return $value1 === $value2;
        }
    }

    /**
     * @return int
     */
    function hash($value)
    {
        switch (\gettype($value)) {
            case 'NULL':
                return 0;

            case 'boolean':
                return $value ? 1231 : 1237;

            case 'object':
                if ($value instanceof Value) {
                    return $value->hashCode();
                }
                return ObjectEqualityRegistry::hash($value) ?? \crc32(\spl_object_hash($value));

            case 'array':
                return hashKeysAndValues($value);

            case 'integer':
                return $value;

            default:
                return \crc32((string)$value); // Note: crc32 range is only 32bits but int is 64bits on most systems
        }
    }

    /**
     * @param iterable $values
     * @return int
     */
    function hashValues($values)
    {
        $hashCode = 1;
        foreach ($values as $value) {
            if ($hashCode === \PHP_INT_MIN) {
                $hashCode = add(add($hashCode << 5, $hashCode), hash($value));
            } else {
                $hashCode = add(add($hashCode << 5, -$hashCode), hash($value));
            }
        }
        return $hashCode;
    }

    /**
     * @param iterable $keysAndValues
     * @return int
     */
    function hashKeysAndValues($keysAndValues)
    {
        $hashCode = 1;
        foreach ($keysAndValues as $key => $value) {
            if ($hashCode === \PHP_INT_MIN) {
                $hashCode = add(add($hashCode << 5, $hashCode), hash($key) ^ hash($value));
            } else {
                $hashCode = add(add($hashCode << 5, -$hashCode), hash($key) ^ hash($value));
            }
        }
        return $hashCode;
    }
}

namespace Value\IntMath
{
    /**
     * @return int
     */
    function multiplyBy31(int $n)
    {
        if ($n === \PHP_INT_MIN) {
            return add($n << 5, $n);
        }
        return add($n << 5, -$n);
    }

    /**
     * based on https://github.com/marcospassos/phpcommon-intmath
     *
     * @return int
     */
    function add(int $a, int $b)
    {
        $native = $a + $b;
        if (\is_int($native)) {
            return $native;
        }

        do {
            // Carry now contains common set bits of the addends
            $carry = $a & $b;
            // Sum of bits of $x and $y,
            // where at least one of the bits is not set
            $a ^= $b;
            // Left-shift by one
            $b = $carry << 1;
        } while ($b !== 0);

        return $a;
    }

    /**
     * @return int
     */
    function negate(int $a)
    {
        if ($a === \PHP_INT_MIN) {
            return $a;
        }
        return -$a;
    }
}
