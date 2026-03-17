<?php

namespace App\Shared\Application;

final readonly class PaginatedResponse
{
  public function __construct(
    public array $data,
    public int   $total,
    public int   $page,
    public int   $limit,
    public int   $totalPages,
  ) {}

  public static function of(array $data, int $total, int $page, int $limit): self
  {
    return new self(
      data: $data,
      total: $total,
      page: $page,
      limit: $limit,
      totalPages: (int) ceil($total / $limit),
    );
  }
}
