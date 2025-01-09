<?php

declare(strict_types=1);

namespace App\Order\DTO;

use App\Order\Enum\OrderState;
use App\Shared\Enum\Currency;
use App\Shared\Symfony\Request\Validation\BaseRequest;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class CreateOrderDTO implements BaseRequest
{
    #[NotBlank]
    #[Required]
    #[Length(min: 1, max: 255)]
    private string $name;

    #[NotBlank]
    #[Assert\Type(type: Currency::class)]
    private Currency $currency;

    /**
     * @var array<int, OrderItemDTO> $items
     */
    #[Assert\All([
        new Assert\Type(type: OrderItemDTO::class)
    ])]
    #[Assert\Valid]
    private array $items;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
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
    }

    /**
     * @return array<int, OrderItemDTO>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array<int, OrderItemDTO> $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
