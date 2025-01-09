<?php

declare(strict_types=1);

namespace App\Shared\Repository;

use App\Shared\Symfony\Request\Query\QueryParamsProvider;
use Pagerfanta\PagerfantaInterface;

/**
 * @template T
 */
interface EntityRepository
{
    /**
     * @param int $id
     * @return T|null
     */
    public function findById(int $id): mixed;

    /**
     * @return array<T>
     */
    public function findAll(): array;

    /**
     * @param QueryParamsProvider $queryParamsProvider
     * @return PagerfantaInterface<T>
     */
    public function findAllPaginated(QueryParamsProvider $queryParamsProvider): PagerfantaInterface;
}
