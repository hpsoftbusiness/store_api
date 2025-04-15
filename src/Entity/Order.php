<?php
// src/Entity/Order.php

namespace App\Entity;

use App\Enum\OrderStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    private  \DateTime  $createdAt;

    #[ORM\Column(type: 'string', enumType: OrderStatus::class)]
    private OrderStatus $status;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    #[ORM\Column(type: 'float')]
    private float $total;

    public function __construct()
    {
        $this->id = 1;//Uuid::v4();
        $number = random_int(1, 100);
        $this->id = $number;
        $this->createdAt = new \DateTime();
        $this->status = OrderStatus::NEW;
        $this->items = new ArrayCollection();
        $this->total = 0;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): void
    {
        $this->status = $status;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): void
    {
        $this->items[] = $item;
        $item->setOrder($this);
        $this->total += $item->getPrice() * $item->getQuantity();
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}