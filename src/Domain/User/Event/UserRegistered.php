<?php

namespace App\Domain\User\Event;

use App\Shared\Domain\Event\DomainEvent;

final class UserRegistered extends DomainEvent
{
  public function __construct(
    public readonly string $userId,
    public readonly string $name,
    public readonly string $email,
    public readonly string $profile,
  ) {
    parent::__construct();
  }

  public function eventName(): string
  {
    return 'identity.user.registered';
  }
}
