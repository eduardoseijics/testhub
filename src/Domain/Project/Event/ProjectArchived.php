<?php

namespace App\Project\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final class ProjectArchived extends DomainEvent
{
    public function __construct(
        public readonly string $projectId,
    ) {
        parent::__construct();
    }

    public function eventName(): string { return 'project.project.archived'; }
}
