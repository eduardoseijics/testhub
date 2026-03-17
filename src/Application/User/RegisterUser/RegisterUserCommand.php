<?php

namespace App\Application\User\RegisterUser;

final readonly class RegisterUserCommand
{
  public function __construct(
    public string $name,
    public string $email,
    public string $password,
    public string $profile,
  ) {}
}
