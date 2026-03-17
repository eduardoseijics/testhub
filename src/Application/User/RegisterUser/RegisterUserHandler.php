<?php

namespace App\Application\User\RegisterUser;

use App\Application\User\RegisterUser\RegisterUserCommand;
use App\Domain\User\Entity\User;
use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\UserName;
use App\Shared\Domain\PasswordHasherInterface;
use App\Shared\Domain\UuidGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class RegisterUserHandler
{
  public function __construct(
    private UserRepositoryInterface  $users,
    private UuidGeneratorInterface   $uuidGenerator,
    private PasswordHasherInterface  $hasher,
    private EventDispatcherInterface $dispatcher,
  ) {}

  public function handle(RegisterUserCommand $command): string
  {
    $email = new Email($command->email);

    if ($this->users->existsByEmail($email)) {
      throw new UserAlreadyExistsException("Email already in use");
    }

    $user = User::register(
      id: new UserId($this->uuidGenerator->generate()),
      name: new UserName($command->name),
      email: $email,
      hashedPassword: $this->hasher->hash($command->password),
      profile: $command->profile,
    );

    $this->users->save($user);

    foreach ($user->pullEvents() as $event) {
      $this->dispatcher->dispatch($event, $event->eventName());
    }

    return $user->id()->value();
  }
}
