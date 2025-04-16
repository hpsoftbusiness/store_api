<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends AbstractController
{
    #[Route('/api/orders', name: 'api_orders', methods: ['POST'])]
    public function create(Request $request, OrderService $orderService): JsonResponse
    {;
        $data = json_decode($request->getContent(), true);
        $order = $orderService->make($data);

        return $this->json($order);
    }

    #[Route('/api/orders/{id}', name: 'get_order', methods: ['GET'])]
    public function getOrder(string $id, OrderService $orderService): JsonResponse
    {
        $order = $orderService->getOrder($id);

        return $this->json($order);
    }

    #[Route('/api/orders/{id}', name: 'update_order_status', methods: ['PATCH'])]
    public function updateStatus(string $id, Request $request, OrderService $orderService): JsonResponse
    {
        $order = $orderService->updateStatus($id, $request->getContent());

        return $this->json($order);
    }
}
