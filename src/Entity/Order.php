<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\DispatchOrder;
use App\Controller\OrderPost;

/**
 * @ApiResource(collectionOperations={
 *     "get",
*      "post"={
 *         "method"="POST",
 *         "path"="/orders",
 *         "controller"=OrderPost::class
 *     },
 *     "by_shopper_and_date"={
 *         "method"="GET",
 *         "path"="/orders/shopper/{shopperId}/{deliveryDate}",
 *         "controller"=DispatchOrder::class,
 *         "swagger_context" = {
 *                  "description"="Retrieves the collection of Order by a shopper for a specific date.",
 *                  "parameters" = {
 *                      {
 *                          "name" = "shopperId",
 *                          "required" = true,
 *                          "type" = "string",
 *                          "in" = "path",
 *                          "description" = "Shopper"
 *                      },
 *                      {
 *                          "name" = "deliveryDate",
 *                          "required" = true,
 *                          "type" = "string",
 *                          "in" = "path",
 *                          "description" = "Date of orders"
 *                      }
 *                  }
 *          }
 *     }
 * })
 *
 * @ApiFilter(DateFilter::class, properties={"deliveryDate": DateFilter::EXCLUDE_NULL})
 * @ApiFilter(SearchFilter::class, properties={"shopper": "exact"})
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="orders")
     * @ORM\JoinTable{
     *      name={order_product}
     *      joinColumns={@ORM\JoinColumn(name="order_id")}
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id")}
     * }
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Address")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $address;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $deliveryDate;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank
     */
    private $startDeliveryHour;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank
     */
    private $endDeliveryHour;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="orderShoppers")
     * @ORM\JoinTable{
     *      name={order_user}
     *      joinColumns={@ORM\JoinColumn(name="order_id")}
     *      inverseJoinColumns={@ORM\JoinColumn(name="shopper_id")}
     * }
     */
    private $shoppers;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->shoppers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|OrderProduct[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(\DateTimeInterface $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function getStartDeliveryHour(): ?\DateTimeInterface
    {
        return $this->startDeliveryHour;
    }

    public function setStartDeliveryHour(\DateTimeInterface $startDeliveryHour): self
    {
        $this->startDeliveryHour = $startDeliveryHour;

        return $this;
    }

    public function getEndDeliveryHour(): ?\DateTimeInterface
    {
        return $this->endDeliveryHour;
    }

    public function setEndDeliveryHour(\DateTimeInterface $endDeliveryHour): self
    {
        $this->endDeliveryHour = $endDeliveryHour;

        return $this;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getShoppers(): Collection
    {
        return $this->shoppers;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addOrder($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getOrder() === $this) {
                $product->addOrder(null);
            }
        }

        return $this;
    }

    public function addShopper(User $shopper): self
    {
        if (!$this->shoppers->contains($shopper)) {
            $this->shoppers[] = $shopper;
            $shopper->addOrderShopper($this);
        }

        return $this;
    }

    public function removeShopper(User $shopper): self
    {
        if ($this->shoppers->contains($shopper)) {
            $this->shoppers->removeElement($shopper);
            // set the owning side to null (unless already changed)
            if ($shopper->getOrderShopper() === $this) {
                $shopper->addOrderShopper(null);
            }
        }

        return $this;
    }
}
