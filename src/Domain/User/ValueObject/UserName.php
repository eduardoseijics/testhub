<?php

namespace App\Domain\User\ValueObject;

use App\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class UserName implements ValueObjectInterface
{
  private string $value;

  /**
   * Constructs a new UserName value object
   * @param string $value
   * @throws InvalidArgumentException if the value is invalid
   */
  public function __construct(string $value)
  {
    $this->validate($value);
    $this->value = $value;
  }

  /**
   * Validates domain rules for UserName
   * @return void
   * @throws InvalidArgumentException
   */
  private function validate(string $value): void
  {
    $this->assertNotEmpty($value);
    $this->assertLengthBetween($value, 2, 255);
    $this->assertValidCharacters($value);
    $this->assertNoConsecutiveSpaces($value);
    $this->assertNoLeadingOrTrailingSpaces($value);
  }

  /**
   * Returns the string value of the UserName
   * @return string
   */
  public function value(): string
  {
    return $this->value;
  }

  /**
   * Compares two UserName objects for equality
   * @param UserName $other
   * @return bool
   */
  public function equals(ValueObjectInterface $other): bool
  {
    return $other instanceof self && $this->value() === $other->value();
  }
  /**
   * Asserts that the value is not empty
   * @param string $value
   * @return void
   * @throws InvalidArgumentException
   */
  private function assertNotEmpty(string $value): void
  {
    if (trim($value) === '') {
      throw new \InvalidArgumentException('Name cannot be empty');
    }
  }

  /**
   * Asserts that the value length is between min and max
   * @param string $value
   * @param int $min
   * @param int $max
   * @return void
   * @throws InvalidArgumentException
   */
  private function assertLengthBetween(string $value, int $min, int $max): void
  {
    $len = mb_strlen($value);
    if ($len < $min) {
      throw new InvalidArgumentException("Name must be at least {$min} characters long");
    }
    if ($len > $max) {
      throw new InvalidArgumentException("Name cannot be longer than {$max} characters");
    }
  }

  /**
   * Asserts that the value contains only letters, spaces, apostrophes and hyphens
   * @param string $value
   * @return void
   * @throws InvalidArgumentException
   */
  private function assertValidCharacters(string $value): void
  {
    if (!preg_match('/^[\p{L}\p{M}\'\-\s]+$/u', $value)) {
      throw new InvalidArgumentException('Name can only contain letters, spaces, apostrophes and hyphens');
    }
  }

  /**
   * Asserts that the value does not contain consecutive spaces
   * @param string $value
   * @return void
   * @throws InvalidArgumentException
   */
  private function assertNoConsecutiveSpaces(string $value): void
  {
    if (preg_match('/\s{2,}/', $value)) {
      throw new InvalidArgumentException('Name cannot contain consecutive spaces');
    }
  }

  /**
   * Asserts that the value does not start or end with a space
   * @param string $value
   * @return void
   * @throws InvalidArgumentException
   */
  private function assertNoLeadingOrTrailingSpaces(string $value): void
  {
    if (preg_match('/^\s|\s$/', $value)) {
      throw new InvalidArgumentException('Name cannot start or end with a space');
    }
  }
}