<?php

namespace App\Tests\Controller;

use App\Infrastructure\Http\Controller\PingController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

final class PingControllerTest extends TestCase
{
  public function testPingReturnsPong(): void
  {
    $controller = new PingController();
    $response = $controller();

    self::assertInstanceOf(Response::class, $response);
    self::assertSame(200, $response->getStatusCode());
    self::assertSame('pong', $response->getContent());
  }
}

