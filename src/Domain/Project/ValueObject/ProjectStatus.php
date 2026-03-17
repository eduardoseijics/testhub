<?php

namespace App\Project\Domain\ValueObject;

use App\Shared\Domain\ValueObject\ValueObjectInterface;

final class ProjectStatus implements ValueObjectInterface
{
    private const VALID = ['active', 'archived'];

    private function __construct(private string $value) {}

    public static function active(): self   { return new self('active'); }
    public static function archived(): self { return new self('archived'); }

    public static function from(string $value): self
    {
        if (!in_array($value, self::VALID, true)) {
            throw new \InvalidArgumentException("Invalid project status: {$value}");
        }
        return new self($value);
    }

    public function isArchived(): bool { return $this->value === 'archived'; }
    public function isActive(): bool   { return $this->value === 'active'; }
    public function value(): string    { return $this->value; }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self && $this->value === $other->value();
    }
}
