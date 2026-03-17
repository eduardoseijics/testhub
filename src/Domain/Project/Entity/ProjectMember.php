<?php

namespace App\Project\Domain\Entity;

use App\Identity\Domain\ValueObject\UserId;
use App\Project\Domain\ValueObject\ProjectRole;

final class ProjectMember
{
    private function __construct(
        private UserId             $userId,
        private ProjectRole        $role,
        private \DateTimeImmutable $joinedAt,
    ) {}

    public static function add(UserId $userId, ProjectRole $role): self
    {
        return new self(
            userId:   $userId,
            role:     $role,
            joinedAt: new \DateTimeImmutable(),
        );
    }

    public function changeRole(ProjectRole $newRole): self
    {
        return new self($this->userId, $newRole, $this->joinedAt);
    }

    public function userId(): UserId             { return $this->userId; }
    public function role(): ProjectRole          { return $this->role; }
    public function joinedAt(): \DateTimeImmutable { return $this->joinedAt; }
}
