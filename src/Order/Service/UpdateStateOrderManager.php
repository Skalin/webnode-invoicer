<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Command\ChangeOrderStateCommand;
use App\Order\Command\CreateOrderCommand;
use App\Order\DTO\CreateOrderDTO;
use App\Order\Entity\Order;
use App\Order\Enum\OrderState;

readonly class UpdateStateOrderManager
{
    public function __construct(private ChangeOrderStateCommand $changeOrderStateCommand)
    {
    }

    public function updateState(Order $order, OrderState $state): Order
    {
        $this->changeOrderStateCommand->setEntity($order);
        $this->changeOrderStateCommand->setState($state);
        $this->changeOrderStateCommand->execute();
        return $this->changeOrderStateCommand->getEntity();
    }
}
