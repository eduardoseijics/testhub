<?php

namespace App\Infrastructure\Http\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetProjectController extends AbstractController
{
  public function __construct(    
    private GetProjectHandler $handler
  )
  {
  }

  public function __invoke(string $projectId): JsonResponse
  {
    return $this->json(($this->handler)($projectId));
  }
}