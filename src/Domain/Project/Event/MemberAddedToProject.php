<?php

namespace App\Project\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final class MemberAddedToProject extends DomainEvent
{
    public function __construct(
        public readonly string $projectId,
        public readonly string $userId,
        public readonly string $role,
    ) {
        parent::__construct();
    }

    public function eventName(): string { return 'project.member.added'; }
}
