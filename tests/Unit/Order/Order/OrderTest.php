<?php

namespace App\Tests\Unit\Order\Order;

use App\Order\Entity\Order;
use App\Order\Entity\OrderItem;
use App\Order\Enum\OrderState;
use App\Shared\DTO\Price\PriceValue;
use App\Shared\Enum\Currency;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testHasZeroItems(): void
    {
        $order = new Order();
        $this->assertEquals(0, $order->getItems()->count());
    }

    public function testHasZeroItemsAfterClear(): void
    {
        $order = new Order();
        $order->setCurrency(Currency::EUR);

        $orderItem = new OrderItem();
        $orderItem->setName('Item 1');
        $orderItem->setPrice(100);
        $orderItem->setQuantity(1);

        $order->addItem($orderItem);

        $this->assertEquals(1, $order->getItems()->count());

        $order->clearItems();

        $this->assertEquals(0, $order->getItems()->count());
    }

    public function testHasOneItem(): void
    {
        $order = new Order();
        $order->setCurrency(Currency::EUR);
        $orderItem = new OrderItem();
        $orderItem->setName('Item 1');
        $orderItem->setPrice(100);
        $orderItem->setQuantity(1);

        $order->addItem($orderItem);

        $this->assertEquals(1, $order->getItems()->count());
    }


    public function testHasZeroItemsAfterRemove(): void
    {
        $order = new Order();
        $order->setCurrency(Currency::EUR);
        $orderItem = new OrderItem();
        $orderItem->setName('Item 1');
        $orderItem->setPrice(100);
        $orderItem->setQuantity(1);

        $order->addItem($orderItem);

        $this->assertEquals(1, $order->getItems()->count());

        $order->removeItem($orderItem);

        $this->assertEquals(0, $order->getItems()->count());
    }


    public function testCurrencyChange(): void
    {
        $order = new Order();
        $this->assertEquals(Currency::CZK, $order->getCurrency());

        $order->setCurrency(Currency::EUR);
        $this->assertEquals(Currency::EUR, $order->getCurrency());

        $order->setCurrency(Currency::CZK);
        $this->assertEquals(Currency::CZK, $order->getCurrency());
    }

    public function testOrderState(): void
    {
        $order = new Order();
        $this->assertEquals(OrderState::DRAFT, $order->getState());

        $order->setState(OrderState::PENDING);
        $this->assertEquals(OrderState::PENDING, $order->getState());


        $order->setState(OrderState::PAID);
        $this->assertEquals(OrderState::PAID, $order->getState());


        $order->setState(OrderState::CANCELLED);
        $this->assertEquals(OrderState::CANCELLED, $order->getState());

        $this->expectExceptionMessage('Cannot change state of cancelled order');
        $order->setState(OrderState::PAID);
    }

    public function testTotalPrice(): void
    {
        $order = new Order();
        $order->setCurrency(Currency::EUR);

        $this->assertEquals(new PriceValue(0.00, Currency::EUR), $order->getPrice());

        $orderItem = new OrderItem();
        $orderItem->setName('Item 1');
        $orderItem->setPrice(100);
        $orderItem->setQuantity(1);

        $order->addItem($orderItem);
        $this->assertEquals(new PriceValue(100.00, Currency::EUR), $order->getPrice());

        $orderItem2 = clone ($orderItem);
        $order->addItem($orderItem2);
        $this->assertEquals(new PriceValue(200.00, Currency::EUR), $order->getPrice());

        $orderItem3 = clone ($orderItem);
        $orderItem3->setQuantity(2);
        $order->addItem($orderItem3);

        $this->assertEquals(new PriceValue(400.00, Currency::EUR), $order->getPrice());
    }
}