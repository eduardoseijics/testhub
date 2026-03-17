<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\ValueObjectInterface;

abstract class UuidValueObject implements ValueObjectInterface
{
  private string $value;

  public function __construct(string $value)
  {
    $this->assertValidUuid($value);
    $this->value = $value;
  }

  public static function from(string $value): static
  {
    return new static($value);
  }

  public function value(): string
  {
    return $this->value;
  }

  public function equals(ValueObjectInterface $other): bool
  {
    return $other instanceof static && $this->value === $other->value();
  }

  private function assertValidUuid(string $value): void
  {
    if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value)) {
      throw new \InvalidArgumentException("Invalid UUID: {$value}");
    }
  }
}
