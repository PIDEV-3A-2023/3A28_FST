<?php

namespace App\Entity;

use App\Repository\CartitemRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Produit;
use App\Entity\Shoppingcart;
#[ORM\Entity(repositoryClass: CartitemRepository::class)]
class Cartitem
{  
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?shoppingcart $panier = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $Total = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?produit
    {
        return $this->produit;
    }

    public function setProduit(produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getPanier(): ?shoppingcart
    {
        return $this->panier;
    }

    public function setPanier(?shoppingcart $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->Total;
    }

    public function setTotal(float $Total): self
    {
        $this->Total = $Total;

        return $this;
    }
}
