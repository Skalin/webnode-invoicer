<?php

declare(strict_types=1);

namespace App\Order\Command;

use App\Order\DTO\CreateOrderDTO;
use App\Order\Entity\Order;
use App\Order\Enum\OrderState;
use App\Order\Repository\OrderNumberGenerator;
use App\Order\Service\OrderManager;
use App\Shared\Doctrine\ORM\Manager\DoctrineEntityPersister;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\SharedLockInterface;
use Symfony\Component\Lock\Store\SemaphoreStore;

class CreateOrderCommand
{
    private CreateOrderDTO $model;
    private Order $entity;

    /**
     * @param DoctrineEntityPersister<Order> $entityPersister
     */
    public function __construct(private readonly DoctrineEntityPersister $entityPersister, private readonly OrderNumberGenerator $orderNumberGenerator, private readonly OrderManager $orderManager)
    {
    }

    public function setModel(CreateOrderDTO $model): void
    {
        $this->model = $model;
    }

    public function execute(): void
    {

        $lock = $this->createLock(name: __CLASS__, ttl: 30);
        if (!$lock->acquire(blocking: true)) {
            throw new \RuntimeException(message: 'Cannot acquire the lock');
        }
        try {
            $this->entity = $this->createEntityFromDto();
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

    protected function createEntityFromDto(): Order
    {
        $entity = new Order();
        $entity->setCreatedAt(new \DateTimeImmutable());
        $entity->setState(OrderState::DRAFT);
        $entity->setName($this->model->getName());
        $entity->setOrderNumber($this->orderNumberGenerator->getNextOrderNumber());
        $entity->setCurrency($this->model->getCurrency());
        $this->orderManager->addItemsFromDTO($entity, $this->model);
        $this->entityPersister->create($entity);
        return $entity;
    }


    public function getEntity(): Order
    {
        return $this->entity;
    }
}
