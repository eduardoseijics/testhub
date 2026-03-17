<?php

namespace App\Domain\User\Entity;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\UserName;
use App\Domain\User\ValueObject\UserStatus;
use App\Domain\User\Event\UserRegistered;
use App\Shared\Domain\AggregateRoot;

final class User extends AggregateRoot
{
  private function __construct(
    private UserId             $id,
    private UserName           $name,
    private Email              $email,
    private string             $hashedPassword,
    private string             $profile,
    private array              $permissions,
    private UserStatus         $status,
    private \DateTimeImmutable $createdAt,
  ) {}

  public static function register(
    UserId   $id,
    UserName $name,
    Email    $email,
    string   $hashedPassword,
    string   $profile,
  ): self {
    $user = new self(
      id            : $id,
      name          : $name,
      email         : $email,
      hashedPassword: $hashedPassword,
      profile       : $profile,
      permissions   : [],
      status        : UserStatus::active(),
      createdAt     : new \DateTimeImmutable(),
    );

    $user->recordEvent(new UserRegistered(
      userId : $id->value(),
      name   : $name->value(),
      email  : $email->value(),
      profile: $profile,
    ));

    return $user;
  }


  public function id(): UserId                    { return $this->id; }
  public function name(): UserName                { return $this->name; }
  public function email(): Email                  { return $this->email; }
  public function hashedPassword(): string        { return $this->hashedPassword; }
  public function profile(): string               { return $this->profile; }
  public function permissions(): array            { return $this->permissions; }
  public function status(): UserStatus            { return $this->status; }
  public function createdAt(): \DateTimeImmutable { return $this->createdAt; }
}
