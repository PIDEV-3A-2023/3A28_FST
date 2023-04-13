<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ShoppingcartRepository;

#[ORM\Entity(repositoryClass: ShoppingcartRepository ::class)]
class Shoppingcart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $orderid;

    #[ORM\Column(length:255)]
    private ?string $nom;

    #[ORM\Column(length:255)]
    private ?string $prenom;

    #[ORM\Column(length:255)]
    private ?string $ville;

    #[ORM\Column(length:600)]
    private ?string $adresse;

    #[ORM\Column]
    private ?int $codePostale;

    
  #[ORM\Column(name: "orderdate", type: "date", nullable: false)]
  private \DateTime $orderdate;

    #[ORM\Column]
    private ?float $totalPrice;

    #[ORM\Column(length:255,nullable: true)]
   
    private ?string $sta = 'en cour';

    public function getOrderid(): ?int
    {
        return $this->orderid;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostale(): ?int
    {
        return $this->codePostale;
    }

    public function setCodePostale(int $codePostale): self
    {
        $this->codePostale = $codePostale;

        return $this;
    }

    public function getOrderdate(): ?\DateTimeInterface
    {
        return $this->orderdate;
    }

    public function setOrderdate(\DateTimeInterface $orderdate): self
    {
        $this->orderdate = $orderdate;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getSta(): ?string
    {
        return $this->sta;
    }

    public function setSta(?string $sta): self
    {
        $this->sta = $sta;

        return $this;
    }


}
