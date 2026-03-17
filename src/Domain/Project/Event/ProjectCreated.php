<?php

namespace App\Project\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final class ProjectCreated extends DomainEvent
{
    public function __construct(
        public readonly string $projectId,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $ownerId,
    ) {
        parent::__construct();
    }

    public function eventName(): string { return 'project.project.created'; }
}
