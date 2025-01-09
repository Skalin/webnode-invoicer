<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\DTO\CreateOrderDTO;
use App\Order\Entity\Order;
use App\Order\Entity\OrderItem;
use App\Shared\Doctrine\ORM\Manager\DoctrineEntityPersister;

readonly class OrderManager
{
    /**
     * @param DoctrineEntityPersister<Order> $entityPersister
     */
    public function __construct(private DoctrineEntityPersister $entityPersister)
    {
    }

    public function addItemsFromDTO(Order $entity, CreateOrderDTO $dto): Order
    {
        $entity->clearItems();
        foreach ($dto->getItems() as $item) {
            $entityItem = new OrderItem();
            $entityItem->setName($item->getName());
            $entityItem->setPrice($item->getPrice());
            $entityItem->setQuantity($item->getQuantity());
            $entity->addItem($entityItem);
        }
        return $entity;
    }

    public function updateOrderFromDTO(Order $order, CreateOrderDTO $dto): Order
    {
        $order->setName($dto->getName());
        $order->setCurrency($dto->getCurrency());
        $this->addItemsFromDTO($order, $dto);
        $this->entityPersister->update($order);
        return $order;
    }

    public function deleteOrder(Order $order): void
    {
        $this->entityPersister->delete($order);
    }
}
