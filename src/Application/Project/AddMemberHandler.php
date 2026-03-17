<?php

namespace App\Project\Application\Command;

use App\Application\Project\AddMemberCommand;
use App\Identity\Domain\ValueObject\UserId;
use App\Project\Domain\Repository\ProjectRepositoryInterface;
use App\Project\Domain\ValueObject\ProjectId;
use App\Project\Domain\ValueObject\ProjectRole;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class AddMemberHandler
{
  public function __construct(
    private ProjectRepositoryInterface $projects,
    private EventDispatcherInterface   $dispatcher,
  ) {}

  public function __invoke(AddMemberCommand $command): void
  {
    $project = $this->projects->findById(ProjectId::from($command->projectId));

    if ($project === null) {
      throw new \DomainException("Project not found");
    }

    $project->addMember(
      userId: UserId::from($command->userId),
      role: ProjectRole::from($command->role),
    );

    $this->projects->save($project);

    foreach ($project->pullEvents() as $event) {
      $this->dispatcher->dispatch($event, $event->eventName());
    }
  }
}
