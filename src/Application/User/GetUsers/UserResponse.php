<?php

namespace App\Application\User;

use App\Domain\User\Entity\User;

final readonly class UserResponse
{
  public function __construct(
    public string $id,
    public string $name,
    public string $email,
    public string $profile,
    public string $status,
    public string $createdAt,
  ) {}

  public static function fromEntity(User $user): self
  {
    return new self(
      id       : $user->id()->value(),
      name     : $user->name()->value(),
      email    : $user->email()->value(),
      profile  : $user->profile(),
      status   : $user->status()->value(),
      createdAt: $user->createdAt()->format(\DateTimeInterface::ATOM),
    );
  }
}
