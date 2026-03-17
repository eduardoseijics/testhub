<?php

namespace App\Shared\Domain\Event;

use Symfony\Component\Uid\Uuid;

abstract class DomainEvent
{
  private string $eventId;
  private \DateTimeImmutable $occurredAt;

  public function __construct()
  {
    $this->eventId    = Uuid::v4()->toRfc4122();
    $this->occurredAt = new \DateTimeImmutable();
  }

  abstract public function eventName(): string;

  public function eventId(): string
  {
    return $this->eventId;
  }
  public function occurredAt(): \DateTimeImmutable
  {
    return $this->occurredAt;
  }
}
