<?php

namespace App\Project\Application\Command;

use App\Project\Domain\Entity\Project;
use App\Project\Domain\Repository\ProjectRepositoryInterface;
use App\Project\Domain\ValueObject\ProjectId;
use App\Project\Domain\ValueObject\Slug;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Domain\User\ValueObject\UserId;

final class CreateProjectHandler
{
  public function __construct(
    private ProjectRepositoryInterface $projects,
    private EventDispatcherInterface   $dispatcher,
  ) {}

  public function __invoke(CreateProjectCommand $command): string
  {
    $slug = Slug::fromName($command->name);

    if ($this->projects->existsBySlug($slug)) {
      throw new \DomainException("A project with this name already exists");
    }

    $project = Project::create(
      id: ProjectId::generate(),
      name: $command->name,
      slug: $slug,
      ownerId: UserId::from($command->ownerUserId),
      description: $command->description,
    );

    $this->projects->save($project);

    foreach ($project->pullEvents() as $event) {
      $this->dispatcher->dispatch($event, $event->eventName());
    }

    return $project->id()->value();
  }
}
