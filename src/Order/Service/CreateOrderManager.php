<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Command\CreateOrderCommand;
use App\Order\DTO\CreateOrderDTO;
use App\Order\Entity\Order;

readonly class CreateOrderManager
{
    public function __construct(private CreateOrderCommand $orderCreateCommand)
    {
    }

    public function createOrderFromDTO(CreateOrderDTO $dto): Order
    {
        $this->orderCreateCommand->setModel($dto);
        $this->orderCreateCommand->setModel($dto);
        $this->orderCreateCommand->execute();
        return $this->orderCreateCommand->getEntity();
    }
}
