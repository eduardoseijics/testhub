<?php

namespace App\Project\Domain\ValueObject;

use App\Shared\Domain\ValueObject\ValueObjectInterface;

final class ProjectRole implements ValueObjectInterface
{
    private const VALID = ['admin', 'qa', 'developer'];

    private function __construct(private string $value) {}

    public static function admin(): self     { return new self('admin'); }
    public static function qa(): self        { return new self('qa'); }
    public static function developer(): self { return new self('developer'); }

    public static function from(string $value): self
    {
        if (!in_array($value, self::VALID, true)) {
            throw new \InvalidArgumentException("Invalid project role: {$value}");
        }
        return new self($value);
    }

    public function isAdmin(): bool     { return $this->value === 'admin'; }
    public function value(): string     { return $this->value; }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self && $this->value === $other->value();
    }
}
