<?php

namespace App\Domain\User\ValueObject;

use App\Shared\Domain\ValueObject\ValueObjectInterface;

final class UserStatus implements ValueObjectInterface
{
    private const VALID = ['active', 'inactive', 'suspended'];

    private function __construct(private string $value) {}

    public static function active(): self    { return new self('active'); }
    public static function inactive(): self  { return new self('inactive'); }
    public static function suspended(): self { return new self('suspended'); }

    public static function from(string $value): self
    {
        if (!in_array($value, self::VALID, true)) {
            throw new \InvalidArgumentException("Invalid user status: {$value}");
        }
        return new self($value);
    }

    public function isActive(): bool { return $this->value === 'active'; }
    public function value(): string  { return $this->value; }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self && $this->value === $other->value();
    }
}
