<?php

namespace App\Tests\Unit\Domain\User\Entity;

use App\Domain\User\Entity\User;
use App\Domain\User\Event\UserRegistered;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\UserName;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
  // ------------------------------------------------------------------
  // Helpers
  // ------------------------------------------------------------------

  private function makeUser(
    ?UserId   $id      = null,
    ?UserName $name    = null,
    ?Email    $email   = null,
    string    $profile = 'qa',
  ): User {
    return User::register(
      id: $id      ?? UserId::generate(),
      name: $name    ?? new UserName('João Silva'),
      email: $email   ?? new Email('joao@email.com'),
      hashedPassword: password_hash('Senha@123', PASSWORD_ARGON2ID),
      profile: $profile,
    );
  }

  // ------------------------------------------------------------------
  // Registro
  // ------------------------------------------------------------------

  public function test_register_returns_user_with_correct_data(): void
  {
    $id    = UserId::generate();
    $name  = new UserName('João Silva');
    $email = new Email('joao@email.com');

    $user = User::register(
      id: $id,
      name: $name,
      email: $email,
      hashedPassword: 'hashed',
      profile: 'admin',
    );

    $this->assertTrue($user->id()->equals($id));
    $this->assertTrue($user->name()->equals($name));
    $this->assertTrue($user->email()->equals($email));
    $this->assertSame('admin', $user->profile());
    $this->assertSame('hashed', $user->hashedPassword());
    $this->assertEmpty($user->permissions());
  }

  public function test_register_sets_status_as_active(): void
  {
    $user = $this->makeUser();

    $this->assertTrue($user->status()->isActive());
  }

  public function test_register_sets_created_at(): void
  {
    $before = new \DateTimeImmutable();
    $user   = $this->makeUser();
    $after  = new \DateTimeImmutable();

    $this->assertGreaterThanOrEqual($before, $user->createdAt());
    $this->assertLessThanOrEqual($after, $user->createdAt());
  }

  // ------------------------------------------------------------------
  // Eventos
  // ------------------------------------------------------------------

  public function test_register_records_user_registered_event(): void
  {
    $user   = $this->makeUser();
    $events = $user->pullEvents();

    $this->assertCount(1, $events);
    $this->assertInstanceOf(UserRegistered::class, $events[0]);
  }

  public function test_user_registered_event_has_correct_data(): void
  {
    $id    = UserId::generate();
    $name  = new UserName('Maria Souza');
    $email = new Email('maria@email.com');

    $user   = $this->makeUser(id: $id, name: $name, email: $email, profile: 'qa');
    $events = $user->pullEvents();

    /** @var UserRegistered $event */
    $event = $events[0];

    $this->assertSame($id->value(), $event->userId);
    $this->assertSame('Maria Souza', $event->name);
    $this->assertSame('maria@email.com', $event->email);
    $this->assertSame('qa', $event->profile);
  }

  public function test_pull_events_clears_event_list(): void
  {
    $user = $this->makeUser();
    $user->pullEvents();

    $this->assertEmpty($user->pullEvents());
  }

  public function test_pull_events_returns_events_only_once(): void
  {
    $user = $this->makeUser();

    $first  = $user->pullEvents();
    $second = $user->pullEvents();

    $this->assertCount(1, $first);
    $this->assertCount(0, $second);
  }
}
