<?php

namespace App\Domain\UserProject\Entity;

use App\Domain\UserProject\Enum\UserProjectRole;
use App\Domain\User\ValueObject\UserId;
use App\Domain\Project\ValueObject\ProjectId;

class UserProject
{
    public function __construct(
        private UserId $userId,
        private ProjectId $projectId,
        private UserProjectRole $role,
        private \DateTimeImmutable $joinedAt = new \DateTimeImmutable(),
    ) {}

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }

    public function getRole(): UserProjectRole
    {
        return $this->role;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function hasPermission(UserProjectRole $minimum): bool
    {
        $hierarchy = [
            UserProjectRole::VIEWER => 0,
            UserProjectRole::MEMBER => 1,
            UserProjectRole::ADMIN  => 2,
            UserProjectRole::OWNER  => 3,
        ];

        return $hierarchy[$this->role] >= $hierarchy[$minimum];
    }

    public function changeRole(UserProjectRole $newRole): void
    {
        $this->role = $newRole;
    }
}