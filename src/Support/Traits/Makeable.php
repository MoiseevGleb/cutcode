<?php

namespace Support\Traits;

trait Makeable
{
    public static function make(mixed ...$args): static
    {
        return new self(...$args);
    }
}
