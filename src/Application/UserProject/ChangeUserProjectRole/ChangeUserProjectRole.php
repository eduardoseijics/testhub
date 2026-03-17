<?php

namespace App\Application\UserProject\ChangeUserProjectRole;

class ChangeUserProjectRoleHandler
{
  public function handle(ChangeUserProjectRoleCommand $command): void
  {
    $executor = $this->repository->findByUserAndProject(
      $command->executorId,
      $command->projectId
    );

    if (!$executor->hasPermission(UserProjectRole::ADMIN)) {
      throw new InsufficientPermissionException();
    }

    $target = $this->repository->findByUserAndProject(
      $command->targetUserId,
      $command->projectId
    );

    $target->changeRole($command->newRole);

    $this->repository->save($target);
  }
}
