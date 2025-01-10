<?php

declare(strict_types=1);

namespace App\Order\Repository;

use App\Order\Entity\Order;
use App\Shared\Repository\EntityRepository;
use App\Shared\Symfony\Request\Query\QueryParamsProvider;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;

/**
 * @implements EntityRepository<Order>
 */
interface OrderRepository extends EntityRepository
{

    public function findById(int $id): mixed;

    public function findAllPaginated(QueryParamsProvider $queryParamsProvider): PagerfantaInterface;

    public function findAll(): array;
}
