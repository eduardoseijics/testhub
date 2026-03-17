<?php

namespace App\Application\User;

use App\Application\User\UserResponse;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Shared\Application\PaginatedQuery;
use App\Shared\Application\PaginatedResponse;

final class GetUsersHandler
{
  public function __construct(
    private UserRepositoryInterface $users,
  ) {}

  public function handle(PaginatedQuery $query): PaginatedResponse
  {
    $users = $this->users->findPaginated(
      limit: $query->limit,
      offset: $query->offset(),
    );

    $total = $this->users->countAll();

    return PaginatedResponse::of(
      data: array_map(fn($user) => UserResponse::fromEntity($user), $users),
      total: $total,
      page: $query->page,
      limit: $query->limit,
    );
  }
}
