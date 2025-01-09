<?php

declare(strict_types=1);

namespace App\Order\Repository;

use App\Order\Service\OrderQuery;
use Doctrine\ORM\NoResultException;

readonly class OrderNumberGenerator
{
    public function __construct(private OrderQuery $query)
    {
    }

    public function getNextOrderNumber(): string
    {
        try {
            $lastOrderNumber = $this->query
                ->getQueryBuilder()
                ->select('o.orderNumber')
                ->andWhere('YEAR(o.createdAt) = :year')
                ->orderBy('o.orderNumber', 'DESC')
                ->setParameter('year', date('Y'))
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            $lastOrderNumber = null;
        }
        /** @var ?string $lastOrderNumber */
        if ($lastOrderNumber === null) {
            return $this->formatOrderNumber(1);
        }

        return $this->formatOrderNumber($this->extractRowFromOrderNumber($lastOrderNumber) + 1);
    }

    protected function extractRowFromOrderNumber(string $orderNumber): int
    {
        if (preg_match('{^(\d{4})(\d{4,})}', $orderNumber, $matches) !== 1) {
            throw new \InvalidArgumentException('Invalid invoice number format');
        }
        return (int)$matches[2];
    }

    protected function formatOrderNumber(int $orderNumber): string
    {
        return sprintf('%s%s', date('Y'), str_pad((string)$orderNumber, 4, '0', STR_PAD_LEFT));
    }
}
