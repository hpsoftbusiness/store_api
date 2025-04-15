<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends AbstractController
{
    #[Route('/api/orders', name: 'api_orders', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $order = new Order();

        $total = 0;

        foreach ($data['items'] as $itemData) {
            $item = new OrderItem();
            $item->setProductId($itemData['productId']);
            $item->setProductName($itemData['productName']);
            $item->setPrice($itemData['price']);
            $item->setQuantity($itemData['quantity']);
            $order->addItem($item);
            $total += $itemData['price'] * $itemData['quantity'];
        }

        $em->persist($order);
        $em->flush();

        return $this->json([
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
        ]);
    }

    #[Route('/api/orders/{id}', name: 'get_order', methods: ['GET'])]
    public function getOrder(string $id, EntityManagerInterface $em): JsonResponse
    {
        $order = $em->getRepository(Order::class)->find($id);

        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }

        $items = $order->getItems();
        $total = 0;

        foreach ($items as $item) {
            $total += $item->getPrice() * $item->getQuantity(); // ✅ poprawnie
        }

        return $this->json([
            'id' => $order->getId(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
            'status' => $order->getStatus(),
            'items' => $items,
            'total' => $total
        ]);
    }

    #[Route('/api/orders/{id}', name: 'update_order_status', methods: ['PATCH'])]
    public function updateStatus(
        string                 $id,
        Request                $request,
        OrderRepository        $orderRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $order = $orderRepository->find($id);

        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['status'])) {
            return new JsonResponse(['error' => 'Missing status'], 400);
        }

        $validStatuses = ['new', 'paid', 'shipped', 'cancelled'];
        if (!in_array($data['status'], $validStatuses)) {
            return new JsonResponse(['error' => 'Invalid status'], 400);
        }

        $order->setStatus((OrderStatus::from($data['status'])));
        $em->flush();

        return new JsonResponse([
            'message' => 'Order status updated',
            'id' => $order->getId(),
            'status' => $order->getStatus(),
        ]);
    }
}
