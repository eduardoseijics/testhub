<?php

namespace App\Project\Domain\Entity;

use App\Domain\User\ValueObject\UserId;
use App\Project\Domain\Event\MemberAddedToProject;
use App\Project\Domain\Event\ProjectArchived;
use App\Project\Domain\Event\ProjectCreated;
use App\Project\Domain\ValueObject\ProjectId;
use App\Project\Domain\ValueObject\ProjectRole;
use App\Project\Domain\ValueObject\ProjectStatus;
use App\Project\Domain\ValueObject\Slug;
use App\Shared\Domain\AggregateRoot;

final class Project extends AggregateRoot
{
    /** @var ProjectMember[] */
    private array $members = [];

    private function __construct(
        private ProjectId          $id,
        private string             $name,
        private Slug               $slug,
        private ?string            $description,
        private UserId             $ownerId,
        private ProjectStatus      $status,
        private \DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        ProjectId $id,
        string    $name,
        Slug      $slug,
        UserId    $ownerId,
        ?string   $description = null,
    ): self {
        $project = new self(
            id:          $id,
            name:        $name,
            slug:        $slug,
            description: $description,
            ownerId:     $ownerId,
            status:      ProjectStatus::active(),
            createdAt:   new \DateTimeImmutable(),
        );

        $project->members[] = ProjectMember::add($ownerId, ProjectRole::admin());

        $project->recordEvent(new ProjectCreated(
            projectId: $id->value(),
            name:      $name,
            slug:      $slug->value(),
            ownerId:   $ownerId->value(),
        ));

        return $project;
    }

    public function addMember(UserId $userId, ProjectRole $role): void
    {
        $this->assertNotArchived();
        $this->assertNotAlreadyMember($userId);

        $this->members[] = ProjectMember::add($userId, $role);

        $this->recordEvent(new MemberAddedToProject(
            projectId: $this->id->value(),
            userId:    $userId->value(),
            role:      $role->value(),
        ));
    }

    public function removeMember(UserId $userId): void
    {
        $this->assertNotArchived();
        $this->assertIsNotOwner($userId);

        $this->members = array_values(
            array_filter($this->members, fn(ProjectMember $m) => !$m->userId()->equals($userId))
        );
    }

    public function changeMemberRole(UserId $userId, ProjectRole $newRole): void
    {
        $this->assertNotArchived();

        $this->members = array_map(
            fn(ProjectMember $m) => $m->userId()->equals($userId) ? $m->changeRole($newRole) : $m,
            $this->members
        );
    }

    public function archive(): void
    {
        $this->assertNotArchived();
        $this->status = ProjectStatus::archived();
        $this->recordEvent(new ProjectArchived(projectId: $this->id->value()));
    }

    public function isMember(UserId $userId): bool
    {
        return (bool) $this->findMember($userId);
    }

    public function id(): ProjectId              { return $this->id; }
    public function name(): string               { return $this->name; }
    public function slug(): Slug                 { return $this->slug; }
    public function description(): ?string       { return $this->description; }
    public function ownerId(): UserId            { return $this->ownerId; }
    public function status(): ProjectStatus      { return $this->status; }
    public function members(): array             { return $this->members; }
    public function createdAt(): \DateTimeImmutable { return $this->createdAt; }

    private function assertNotArchived(): void
    {
        if ($this->status->isArchived()) {
            throw new \DomainException("Cannot modify an archived project");
        }
    }

    private function assertNotAlreadyMember(UserId $userId): void
    {
        if ($this->isMember($userId)) {
            throw new \DomainException("User is already a member of this project");
        }
    }

    private function assertIsNotOwner(UserId $userId): void
    {
        if ($this->ownerId->equals($userId)) {
            throw new \DomainException("Cannot remove the project owner");
        }
    }

    private function findMember(UserId $userId): ?ProjectMember
    {
        foreach ($this->members as $member) {
            if ($member->userId()->equals($userId)) {
                return $member;
            }
        }
        return null;
    }
}
