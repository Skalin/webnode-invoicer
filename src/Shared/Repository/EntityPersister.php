<?php

declare(strict_types=1);

namespace App\Shared\Repository;

/**
 * @template T
 */
interface EntityPersister
{
    /**
     * @param T $entity
     */
    public function create(mixed $entity, bool $flush = true): void;

    /**
     * @param T $entity
     */
    public function update(mixed $entity, bool $flush = true): void;

    /**
     * @param T $entity
     */
    public function delete(mixed $entity, bool $flush = true): void;
}
