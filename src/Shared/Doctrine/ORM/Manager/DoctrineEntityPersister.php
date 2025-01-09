<?php

declare(strict_types=1);

namespace App\Shared\Doctrine\ORM\Manager;

use App\Shared\Repository\EntityPersister;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @template T
 * @implements EntityPersister<T>
 */
readonly class DoctrineEntityPersister implements EntityPersister
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(mixed $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();

        $em->persist($entity);
        if ($flush) {
            $em->flush();
        }
    }

    public function update(mixed $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();

        if ($flush) {
            $em->flush();
        }
    }

    public function delete(mixed $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();

        $em->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
