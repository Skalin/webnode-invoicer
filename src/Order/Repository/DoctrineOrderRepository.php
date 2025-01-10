<?php

declare(strict_types=1);

namespace App\Order\Repository;

use App\Order\Entity\Order;
use App\Shared\Symfony\Request\Query\QueryParamsProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;

/**
 * @extends ServiceEntityRepository<Order>
 */
class DoctrineOrderRepository extends ServiceEntityRepository implements OrderRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findById(int $id): mixed
    {
        return $this->find($id);
    }

    public function findAllPaginated(QueryParamsProvider $queryParamsProvider): PagerfantaInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryAdapter = new QueryAdapter($queryBuilder);
        return Pagerfanta::createForCurrentPageWithMaxPerPage(
            $queryAdapter,
            $queryParamsProvider->getCurrentPage(Order::class),
            $queryParamsProvider->getMaxPerPage(Order::class)
        );
    }

    public function findAll(): array
    {
        return parent::findAll();
    }
}
