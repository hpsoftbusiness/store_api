<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderService {

    public function __construct(private EntityManagerInterface $manager, private OrderRepository $orderRepository) {}

    public function make($data)
    {
        $order = new Order();

        $total = 0;

        foreach ($data['items'] as $itemData) {
            $item = new OrderItem();
            $item->setProductId($itemData['productId']);
            $item->setProductName($itemData['productName']);
            $item->setPrice($itemData['price']);
            $item->setQuantity($itemData['quantity']);
            $order->addItem($item);
        }

        $this->manager->persist($order);
        $this->manager->flush();

        return [
            'id' => $order->getId(),
            'status' => $order->getStatus(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
            'total' => $order->getTotal(),
            'items' => array_map(function (OrderItem $item) {
                return [
                    'productId' => $item->getProductId(),
                    'productName' => $item->getProductName(),
                    'price' => $item->getPrice(),
                    'quantity' => $item->getQuantity(),
                ];
            }, $order->getItems()->toArray()),
        ];
    }

    public function getOrder($id)
    {
        $order = $this->manager->getRepository(Order::class)->find($id);

        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }

        $items = $order->getItems();
        $total = 0;

        foreach ($items as $item) {
            $total += $item->getPrice() * $item->getQuantity();
        }

        return [
            'id' => $order->getId(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
            'status' => $order->getStatus(),
            'items' => $items,
            'total' => $total
        ];
    }

    public function updateStatus(string $id, $content)
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        $data = json_decode($content, true);
        if (!isset($data['status'])) {
            return new JsonResponse(['error' => 'Missing status'], 400);
        }

        $validStatuses = ['new', 'paid', 'shipped', 'cancelled'];
        if (!in_array($data['status'], $validStatuses)) {
            return new JsonResponse(['error' => 'Invalid status'], 400);
        }

        $order->setStatus((OrderStatus::from($data['status'])));
        $this->manager->flush();

        return ['status' => $order->getStatus()];
    }
}