<?php
namespace Value;

interface Value
{
    function equals($value): bool;
    function hashCode(): int;
}
