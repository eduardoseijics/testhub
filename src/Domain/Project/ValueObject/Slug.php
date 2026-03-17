<?php

namespace App\Project\Domain\ValueObject;

use App\Shared\Domain\ValueObject\ValueObjectInterface;

final class Slug implements ValueObjectInterface
{
  private string $value;

  public function __construct(string $value)
  {
    $this->assertValidFormat($value);
    $this->value = $value;
  }

  public static function fromName(string $name): self
  {
    $slug = strtolower($name);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', trim($slug));

    return new self($slug);
  }

  public function value(): string
  {
    return $this->value;
  }

  public function equals(ValueObjectInterface $other): bool
  {
    return $other instanceof self && $this->value === $other->value();
  }

  private function assertValidFormat(string $value): void
  {
    if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
      throw new \InvalidArgumentException("Invalid slug format: {$value}");
    }
  }
}
