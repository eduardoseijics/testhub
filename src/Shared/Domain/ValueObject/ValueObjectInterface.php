<?php

namespace App\Shared\Domain\ValueObject;

interface ValueObjectInterface
{
    public function value(): mixed;
    public function equals(ValueObjectInterface $other): bool;
}
