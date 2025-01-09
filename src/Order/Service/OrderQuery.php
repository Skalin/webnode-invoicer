<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Repository\OrderRepository;

class OrderQuery
{
    public function __construct(private readonly OrderRepository $repository)
    {
    }


    public function getQueryBuilder(string $alias = 'o'): \Doctrine\ORM\QueryBuilder
    {
        return $this->repository->createQueryBuilder(
            alias: $alias
        );
    }
}
