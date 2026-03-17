<?php

namespace App\Project\Domain\Repository;

use App\Project\Domain\Entity\Project;
use App\Project\Domain\ValueObject\ProjectId;
use App\Project\Domain\ValueObject\Slug;

interface ProjectRepositoryInterface
{
    public function save(Project $project): void;
    public function findById(ProjectId $id): ?Project;
    public function findBySlug(Slug $slug): ?Project;
    public function existsBySlug(Slug $slug): bool;
}
