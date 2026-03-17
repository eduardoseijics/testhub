<?php

namespace App\Project\Infrastructure\Http\Controller;

use App\Project\Application\Command\AddMemberCommand;
use App\Project\Application\Command\AddMemberHandler;
use App\Project\Application\Command\CreateProjectCommand;
use App\Project\Application\Command\CreateProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/v1/projects')]
final class ProjectController extends AbstractController
{
    public function __construct(
        private CreateProjectHandler $createProject,
        private AddMemberHandler     $addMember,
    ) {}

    #[Route('', methods: ['POST'])]
    public function create(Request $request, #[CurrentUser] $user): JsonResponse
    {
        $body = $request->toArray();

        $projectId = ($this->createProject)(new CreateProjectCommand(
            name:        $body['name'],
            ownerUserId: $user->getId(),
            description: $body['description'] ?? null,
        ));

        return $this->json(['id' => $projectId], 201);
    }

    #[Route('/{projectId}/members', methods: ['POST'])]
    public function addMember(string $projectId, Request $request): JsonResponse
    {
        $body = $request->toArray();

        ($this->addMember)(new AddMemberCommand(
            projectId: $projectId,
            userId:    $body['user_id'],
            role:      $body['role'],
        ));

        return $this->json(null, 204);
    }
}
