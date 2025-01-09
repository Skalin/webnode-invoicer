<?php

declare(strict_types=1);

namespace App\Order\DTO;

use App\Shared\Symfony\Request\Validation\BaseRequest;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Contracts\Service\Attribute\Required;

class OrderItemDTO implements BaseRequest
{
    #[NotBlank]
    #[Required]
    #[Length(min: 1, max: 255)]
    private string $name;

    #[NotBlank]
    #[NotEqualTo(value: 0)]
    #[Required]
    private float $price;

    #[NotBlank]
    #[PositiveOrZero]
    #[Required]
    private float $quantity;

    public function __construct(string $name, float $price, float $quantity)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }
}
