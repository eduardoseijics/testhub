<?php

namespace App\Infrastructure\Http\Controller\User;

use App\Application\User\GetUsersHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetUsersController extends AbstractController
{
  public function __construct(
    private GetUsersHandler $handler,
  ) {}

  #[Route('/api/v1/users', methods: ['GET'])]
  public function __invoke(): JsonResponse
  {
    return $this->json(($this->handler)());
  }
}
