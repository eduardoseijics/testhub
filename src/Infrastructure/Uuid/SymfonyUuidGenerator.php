<?php

namespace App\Infrastructure\Uuid;

use App\Shared\Domain\UuidGeneratorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\Uid\Uuid;

#[AsAlias(UuidGeneratorInterface::class)]
class SymfonyUuidGenerator implements UuidGeneratorInterface
{
  public function generate(): string
  {
    return Uuid::v7()->toRfc4122();
  }
}
