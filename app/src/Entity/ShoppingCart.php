<?php

namespace App\Entity;

use App\Repository\ShoppingCartRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShoppingCartRepository::class)
 */
class ShoppingCart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private array $productIds = [];

    /**
     * @ORM\Column(type="array")
     */
    private array $summersIds = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductIds(): ?array
    {
        return $this->productIds;
    }

    public function setProductIds(array $productIds): self
    {
        $this->productIds = $productIds;

        return $this;
    }

    public function getSummersIds(): ?array
    {
        return $this->summersIds;
    }

    public function setSummersIds(array $summersIds): self
    {
        $this->summersIds = $summersIds;

        return $this;
    }
}
