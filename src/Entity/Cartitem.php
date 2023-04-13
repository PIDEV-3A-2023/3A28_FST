<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CartitemRepository;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CartitemRepository ::class)]
class Cartitem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $cartId;

    #[ORM\Column(nullable: false)]
    private ?int $quantity;

   #[ORM\Column]
    private ?float $price;

  
    #[ORM\ManyToOne(targetEntity: Shoppingcart::class)]
    #[ORM\JoinColumn(name: "orderid", referencedColumnName: "orderId")]
    private ?int $orderid;


    
    #[ORM\OneToMany(mappedBy:'idProduit',targetEntity: Produit::class)]
    private Collection $ProduitClass;

    
    public function getCartId(): ?int
    {
        return $this->cartId;
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

    public function getOrderid(): ?Shoppingcart
    {
        return $this->orderid;
    }

    public function setOrderid(?Shoppingcart $orderid): self
    {
        $this->orderid = $orderid;

        return $this;
    }

    public function getId(): ?Produit
    {
        return $this->id;
    }

    public function setId(?Produit $id): self
    {
        $this->id = $id;

        return $this;
    }


}
