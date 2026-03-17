<?php

namespace App\Application\Project;

class ChangeUserProjectRoleCommand
{
  public function __construct(
    public readonly string $executorId,
    public readonly string $targetUserId,
    public readonly string $projectId,
    public readonly string $newRole,
  ) {}
}
