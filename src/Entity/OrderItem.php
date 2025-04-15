<?php

// src/Entity/OrderItem.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $productId;

    #[ORM\Column(type: 'string')]
    private string $productName;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    private Order $order;

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}