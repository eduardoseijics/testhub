<?php

namespace App\Infrastructure\Http\Controller\User;

use App\Application\User\RegisterUser\RegisterUserCommand;
use App\Application\User\RegisterUser\RegisterUserHandler;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterUserController extends AbstractController
{
  public function __construct(
    private RegisterUserHandler $handler,
  ) {}

  #[Route('/users', methods: ['POST'])]
  public function __invoke(Request $request): JsonResponse
  { 
    try {
      $body = $request->toArray();

      $userId = ($this->handler)(new RegisterUserCommand(
        name: $body['name'] ?? '',
        email: $body['email'] ?? '',
        password: $body['password'] ?? '',
        profile: $body['profile'] ?? '',
      ));

      return $this->json(['id' => $userId], Response::HTTP_CREATED);
    } catch (\Symfony\Component\HttpFoundation\Exception\JsonException $e) {
      return $this->json(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
    } catch (DomainException $e) {
      return $this->json(['error' => $e->getMessage()], Response::HTTP_CONFLICT);
    } catch (\InvalidArgumentException $e) {
      return $this->json(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }
}
