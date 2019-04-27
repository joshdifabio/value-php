<?php
namespace Value;

/**
 * A generic interface for value objects.
 *
 * Objects implementing this interface MUST be immutable.
 */
interface Value
{
    /**
     * Indicates whether some other value is "equal to" this one.
     *
     * The equals method implements an equivalence relation on object references:
     *   - It is reflexive: for any reference value x, x.equals(x) should return true.
     *   - It is symmetric: for any reference values x and y, x.equals(y) should return true if and only if y.equals(x)
     *     returns true.
     *   - It is transitive: for any reference values x, y, and z, if x.equals(y) returns true and y.equals(z) returns
     *     true, then x.equals(z) should return true.
     *   - It is consistent: for any reference values x and y, multiple invocations of x.equals(y) consistently return
     *     true or consistently return false.
     *   - For any reference value x, x.equals(null) should return false.
     *
     * Note that it is generally necessary to change the hashCode method whenever this method is changed, so as to
     * maintain the general contract for the hashCode() method, which states that equal objects must have equal hash
     * codes.
     */
    function equals($value): bool;

    /**
     * Returns a hash code for the object. This method is supported for the benefit of hash tables often used in data
     * structures.
     *
     * The general contract of hashCode is:
     *   - Whenever it is invoked on the same object more than once during an execution of a PHP application, the
     *     hashCode method must consistently return the same integer. This integer need not remain consistent from one
     *     execution of an application to another execution of the same application.
     *   - If two objects are equal according to the equals(value) method, then calling the hashCode method on each of
     *     the two objects must produce the same integer result.
     *   - It is not required that if two objects are unequal according to the equals(value) method, then
     *     calling the hashCode method on each of the two objects must produce distinct integer results. However, the
     *     programmer should be aware that producing distinct integer results for unequal objects may improve the
     *     performance of hash tables.
     */
    function hashCode(): int;
}
