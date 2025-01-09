<?php

declare(strict_types=1);

namespace App\Order\Entity;

use App\Shared\Doctrine\ORM\Mapping\IntIdIdentifier;
use App\Shared\DTO\Price\PriceValue;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Attribute\Ignore;

#[Entity]
class OrderItem
{
    use IntIdIdentifier;

    #[ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    private ?Order $order;


    #[Column(type: Types::STRING, length: 255, nullable: false)]
    private string $name;


    #[Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: false)]
    private string $quantity;


    #[Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: false)]
    private string $price;

    #[Ignore]
    public function getId(): int
    {
        return $this->id;
    }

    #[Ignore]
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getQuantity(): float
    {
        return (float)$this->quantity;
    }

    public function setQuantity(float $quantity): void
    {
        $this->quantity = number_format($quantity, 2);
    }

    public function getPrice(): float
    {
        return (float)$this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = number_format($price, 2);
    }

    public function getTotalPrice(): PriceValue
    {
        if ($this->order === null) {
            throw new \RuntimeException('Order is not set');
        }
        return new PriceValue($this->getPrice() * $this->getQuantity(), $this->order->getCurrency());
    }
}
