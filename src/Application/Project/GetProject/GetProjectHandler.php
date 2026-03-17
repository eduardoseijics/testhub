<?php

use App\Project\Domain\Repository\ProjectRepositoryInterface;

class GetProjectHandler
{
  public function __construct(
    private ProjectRepositoryInterface $repository,
  ) {}

  public function __invoke(string $projectId): array
  {
    $project = $this->repository->findById($projectId);

    if (!$project) {
      throw new \DomainException('Project not found');
    }

    return [
      'id' => $project->getId(),
      'name' => $project->getName(),
      'description' => $project->getDescription(),
      'members' => array_map(fn($member) => [
        'userId' => $member->getUserId(),
        'role' => $member->getRole(),
      ], $project->getMembers()),
    ];
  }
}