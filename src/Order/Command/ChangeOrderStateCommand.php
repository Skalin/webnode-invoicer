<?php

declare(strict_types=1);

namespace App\Order\Command;

use App\Order\Entity\Order;
use App\Order\Enum\OrderState;
use App\Shared\Doctrine\ORM\Manager\DoctrineEntityPersister;
use App\Shared\Repository\EntityPersister;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\SharedLockInterface;
use Symfony\Component\Lock\Store\SemaphoreStore;

class ChangeOrderStateCommand
{
    private OrderState $orderState;

    private Order $entity;

    /**
     * @param DoctrineEntityPersister<Order> $entityPersister
     */
    public function __construct(private readonly EntityPersister $entityPersister)
    {
    }

    public function setState(OrderState $orderState): void
    {
        $this->orderState = $orderState;
    }

    public function setEntity(Order $order): void
    {
        $this->entity = $order;
    }

    public function execute(): void
    {

        $lock = $this->createLock(name: __CLASS__, ttl: 30);
        if (!$lock->acquire(blocking: true)) {
            throw new \RuntimeException(message: 'Cannot acquire the lock');
        }
        try {
            $this->updateOrderState();
        } finally {
            $lock->release();
        }
    }

    private function createLock(string $name, ?int $ttl = null): SharedLockInterface
    {

        $store = new SemaphoreStore();
        $factory = new LockFactory($store);

        return $factory->createLock(resource: $name, ttl: $ttl);
    }

    protected function updateOrderState(): void
    {
        $this->entity->setState($this->orderState);
        $this->entityPersister->update($this->entity);
    }

    public function getEntity(): Order
    {
        return $this->entity;
    }
}
