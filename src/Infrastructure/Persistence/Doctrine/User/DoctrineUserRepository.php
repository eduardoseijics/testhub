<?php

namespace App\Infrastructure\Persistence\Doctrine\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\UserId;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineUserRepository implements UserRepositoryInterface
{
  public function __construct(
    private EntityManagerInterface $em,
  ) {}

  public function save(User $user): void
  {
    $this->em->persist($user);
    $this->em->flush();
  }

  public function findById(UserId $id): ?User
  {
    return $this->em->find(User::class, $id->value());
  }

  public function findByEmail(Email $email): ?User
  {
    return $this->em->getRepository(User::class)
      ->findOneBy(['email' => $email->value()]);
  }

  public function existsByEmail(Email $email): bool
  {
    return $this->findByEmail($email) !== null;
  }

  public function findPaginated(int $limit, int $offset): array
  {
    return $this->em->getRepository(User::class)
      ->findBy([], ['createdAt' => 'DESC'], $limit, $offset);
  }

  public function countAll(): int
  {
    return $this->em->getRepository(User::class)->count([]);
  }
}
