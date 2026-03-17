<?php

namespace App\Project\Infrastructure\Persistence;

use App\Project\Domain\Entity\Project;
use App\Project\Domain\Repository\ProjectRepositoryInterface;
use App\Project\Domain\ValueObject\ProjectId;
use App\Project\Domain\ValueObject\Slug;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    public function save(Project $project): void
    {
        $this->em->persist($project);
        $this->em->flush();
    }

    public function findById(ProjectId $id): ?Project
    {
        return $this->em->find(Project::class, $id->value());
    }

    public function findBySlug(Slug $slug): ?Project
    {
        return $this->em->getRepository(Project::class)
            ->findOneBy(['slug' => $slug->value()]);
    }

    public function existsBySlug(Slug $slug): bool
    {
        return $this->findBySlug($slug) !== null;
    }
}