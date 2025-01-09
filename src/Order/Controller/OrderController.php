<?php

declare(strict_types=1);

namespace App\Order\Controller;

use App\Order\DTO\CreateOrderDTO;
use App\Order\DTO\UpdateOrderDTO;
use App\Order\Entity\Order;
use App\Order\Enum\OrderState;
use App\Order\Repository\OrderRepository;
use App\Order\Service\CreateOrderManager;
use App\Order\Service\OrderManager;
use App\Order\Service\UpdateStateOrderManager;
use App\Shared\Symfony\Request\Query\QueryParamsProvider;
use App\Shared\Symfony\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;

#[Route('/order')]
class OrderController extends BaseController
{
    #[Route('', name: 'order_index', methods: ['GET'])]
    public function index(Request $request, OrderRepository $repository): Response
    {
        $queryParamsProvider = new QueryParamsProvider($request);
        $repository->findAllPaginated($queryParamsProvider);
        return $this->sendResponse($repository->findAllPaginated($queryParamsProvider), Response::HTTP_OK);
    }

    #[Route('/{order}', name: 'order_view', requirements: ['order' => '\d+'], methods: ['GET'])]
    public function view(OrderRepository $orderRepository, int $order): Response
    {
        $order = $this->findEntity($orderRepository, $order);
        return $this->sendResponse($order, Response::HTTP_OK);
    }

    #[Route('', name: 'order_create', methods: ['POST'])]
    public function create(CreateOrderManager $createOrderManager, Request $request): Response
    {
        /** @var CreateOrderDTO $orderDto */
        $orderDto = $this->createRequest($request, CreateOrderDTO::class);
        $order = $createOrderManager->createOrderFromDTO($orderDto);

        return $this->sendResponse($order, Response::HTTP_CREATED);
    }

    #[Route('/{order}', name: 'order_update', requirements: ['order' => '\d+'], methods: ['PUT'])]
    public function update(OrderRepository $orderRepository, int $order, Request $request, OrderManager $orderManager): Response
    {
        $order = $this->findEntity($orderRepository, $order);
        /** @var UpdateOrderDTO $orderDto */
        $orderDto = $this->createRequest($request, UpdateOrderDTO::class);
        $order = $orderManager->updateOrderFromDTO($order, $orderDto);

        return $this->sendResponse($order, Response::HTTP_OK);
    }

    #[Route('/{order}', name: 'order_delete', requirements: ['order' => '\d+'], methods: ['DELETE'])]
    public function delete(OrderRepository $orderRepository, int $order, OrderManager $orderManager): Response
    {
        $order = $this->findEntity($orderRepository, $order);
        $orderManager->deleteOrder($order);
        return $this->sendResponse([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/{order}/items', name: 'order_items', requirements: ['order' => '\d+'], methods: ['GET'])]
    public function items(OrderRepository $orderRepository, int $order): Response
    {
        $order = $this->findEntity($orderRepository, $order);
        return $this->sendResponse($order->getItems(), Response::HTTP_OK);
    }

    #[Route('/{order}/change-status/{state}', name: 'order_status_change', requirements: ['order' => '\d+', 'state' => new EnumRequirement(OrderState::class)], methods: ['POST'])]
    public function changeStatus(UpdateStateOrderManager $updateStateOrderManager, OrderRepository $orderRepository, int $order, OrderState $state): Response
    {
        $order = $this->findEntity($orderRepository, $order);
        $updateStateOrderManager->updateState($order, $state);
        return $this->sendResponse($order, Response::HTTP_OK);
    }

    protected function findEntity(OrderRepository $orderRepository, int $order): Order
    {
        $order = $orderRepository->findById($order);
        if ($order === null) {
            throw new NotFoundHttpException('Order not found');
        }
        return $order;
    }
}
