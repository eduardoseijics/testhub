<?php

namespace App\Infrastructure\Security\Password;

use App\Shared\Domain\PasswordHasherInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(PasswordHasherInterface::class)]
final class Argon2PassowrdHasher implements PasswordHasherInterface
{
  public function hash(string $plainPassword): string
  {
    return password_hash($plainPassword, PASSWORD_ARGON2ID);
  }

  public function verify(string $plainPassword, string $hashedPassword): bool
  {
    return password_verify($plainPassword, $hashedPassword);
  }
}
