<?php

declare(strict_types=1);

namespace App\Order\DTO;

use App\Order\Entity\Order;

class UpdateOrderDTO extends CreateOrderDTO
{
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
