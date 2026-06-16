<?php

namespace App\Infrastructure\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PingController extends AbstractController
{
  #[Route('/api/v1/ping', methods: ['GET'])]
  public function __invoke(): Response
  {
    return new Response('pong', Response::HTTP_OK, ['Content-Type' => 'text/plain']);
  }
}