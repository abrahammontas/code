<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="owner")
     */
    private $orders;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Order", mappedBy="shoppers")
     */
    private $orderShoppers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->orderShoppers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getType(): ?UserType
    {
        return $this->type;
    }
    
    public function setType(UserType $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addShopper($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getShopper() === $this) {
                $order->setShopper(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrderShoppers(): Collection
    {
        return $this->orderShoppers;
    }

    public function addOrderShopper(Order $orderShopper): self
    {
        if (!$this->orderShoppers->contains($orderShopper)) {
            $this->orderShoppers[] = $orderShopper;
            $orderShopper->addShopper($this);
        }

        return $this;
    }

    public function removeOrderShopper(Order $orderShopper): self
    {
        if ($this->orderShoppers->contains($orderShopper)) {
            $this->orderShoppers->removeElement($orderShopper);
            // set the owning side to null (unless already changed)
            if ($orderShopper->getShopper() === $this) {
                $orderShopper->addShopper(null);
            }
        }

        return $this;
    }
}
