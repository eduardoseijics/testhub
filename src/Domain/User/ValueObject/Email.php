<?php

namespace App\Domain\User\ValueObject;

use App\Shared\Domain\ValueObject\ValueObjectInterface;

final class Email implements ValueObjectInterface
{
  private string $value;

  public function __construct(string $value)
  {
    $this->assertMaxLength($value);
    $this->assertValidFormat($value);
    $this->value = strtolower($value);
  }

  public static function from(string $value): self
  {
    return new self($value);
  }

  public function value(): string
  {
    return $this->value;
  }

  public function equals(ValueObjectInterface $other): bool
  {
    return $other instanceof self && $this->value === $other->value();
  }

  private function assertMaxLength(string $value): void
  {
    if (strlen($value) > 255) {
      throw new \InvalidArgumentException("Email must not exceed 255 characters");
    }
  }

  private function assertValidFormat(string $value): void
  {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
      throw new \InvalidArgumentException("Invalid email format");
    }
  }
}
