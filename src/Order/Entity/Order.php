<?php

declare(strict_types=1);

namespace App\Order\Entity;

use App\Order\Enum\OrderState;
use App\Order\Repository\OrderRepository;
use App\Shared\Doctrine\ORM\Mapping\IntIdIdentifier;
use App\Shared\DTO\Price\PriceValue;
use App\Shared\Enum\Currency;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Serializer\Attribute\Ignore;

#[Entity(repositoryClass: OrderRepository::class)]
#[Table(name: '`order`')]
#[UniqueConstraint(columns: ['order_number'])]
class Order
{
    use IntIdIdentifier;


    #[Column(nullable: false)]
    private \DateTimeImmutable $createdAt;

    #[Column(type: Types::STRING, length: 12, nullable: false)]
    private string $orderNumber;

    #[Column(nullable: true)]
    private ?string $name;

    #[Column(nullable: false, enumType: Currency::class, options: ['default' => Currency::CZK])]
    private Currency $currency;

    #[Column(nullable: false, enumType: OrderState::class, options: ['default' => OrderState::DRAFT])]
    private OrderState $state;

    /** @var Collection<int, OrderItem> $items */
    #[OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: [
        'persist',
        'remove'
    ], orphanRemoval: true)]
    private Collection $items;

    private ?PriceValue $price;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->currency = Currency::CZK;
        $this->state = OrderState::DRAFT;
        $this->price = new PriceValue(0, $this->currency);
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
        $this->price = null;
    }

    public function getState(): OrderState
    {
        return $this->state;
    }

    public function setState(OrderState $state): void
    {
        if ($this->state === OrderState::CANCELLED) {
            throw new \RuntimeException('Cannot change state of cancelled order');
        }
        $this->state = $state;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param Collection<int, OrderItem> $items
     */
    public function setItems(Collection $items): void
    {
        $this->items = $items;
    }

    public function getPrice(): PriceValue
    {
        if ($this->price === null) {
            $this->price = new PriceValue(
                $this->items->reduce(fn(float $total, OrderItem $item) => $total + (float)$item->getTotalPrice()->getPrice(), 0),
                $this->currency
            );
        }
        return $this->price;
    }

    public function setPrice(PriceValue $price): void
    {
        $this->price = $price;
    }


    public function addItem(OrderItem $item): void
    {
        $this->items->add($item);
        $item->setOrder($this);
        $this->price = null;
    }


    public function removeItem(OrderItem $item): void
    {
        $this->items->removeElement($item);
        $item->setOrder(null);
        $this->price = null;
    }

    public function clearItems(): void
    {
        $this->items->forAll(function (int $key, OrderItem $item) {
            $this->removeItem($item);
            $this->price = null;
            return true;
        });
    }
}
