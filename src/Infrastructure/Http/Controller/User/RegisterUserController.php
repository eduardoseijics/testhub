<?php

namespace App\Infrastructure\Http\Controller\User;

use App\Application\User\RegisterUser\RegisterUserCommand;
use App\Application\User\RegisterUser\RegisterUserHandler;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterUserController extends AbstractController
{
  public function __construct(
    private RegisterUserHandler $handler,
  ) {}

  #[Route('/api/v1/users', methods: ['POST'])]
  public function __invoke(Request $request): JsonResponse
  {
    $body = $request->toArray();

    try {
      $userId = ($this->handler)(new RegisterUserCommand(
        name: $body['name'],
        email: $body['email'],
        password: $body['password'],
        profile: $body['profile'],
      ));

      return $this->json(['id' => $userId], 201);
    } catch (DomainException $e) {
      return $this->json(['error' => $e->getMessage()], 409);
    } catch (\InvalidArgumentException $e) {
      return $this->json(['error' => $e->getMessage()], 422);
    }
  }
}
