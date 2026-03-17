<?php

namespace App\Project\Application\Command;

final readonly class CreateProjectCommand
{
  public function __construct(
    public string  $name,
    public string  $ownerUserId,
    public ?string $description = null,
  ) {}
}
