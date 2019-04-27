# Value PHP

*An interface for immutable value classes for PHP7.1+*

Value objects are one of the fundamental building blocks of modern software.
However, value objects in PHP lack a common interface, making it impossible
to create useful generic solutions to a range of problems, especially
data structures.

This package provides a simple `Value` interface with two methods: `equals()`
and `hashCode()`. Also provided are a number of helper functions, such as
`equal()`, `hash()` and `hashValues()`, which make it easy for you to implement
your own `Value` classes with minimal effort.

## See also

[AutoValue PHP](https://github.com/Space48/auto-value-php): Generated immutable value classes for PHP7.1+
