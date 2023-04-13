<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Repository\ProduitRepository;

#[ORM\Entity(repositoryClass: ProduitRepository ::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idProduit;

    #[ORM\Column(length:255)]
    private ?string $nom;

    #[ORM\Column(length:500)]
    private ?string $description;

    #[ORM\Column]
    private ?float $prix;

    #[ORM\Column(length:255)]
    private ?string $image;

    #[ORM\Column]
    private ?int $qteStock;

   
    #[ORM\ManyToOne(inversedBy:'Categorie')]
    private ?Categorie $idCtg;

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getQteStock(): ?int
    {
        return $this->qteStock;
    }

    public function setQteStock(int $qteStock): self
    {
        $this->qteStock = $qteStock;

        return $this;
    }

    public function getIdCtg(): ?Categorie
    {
        return $this->idCtg;
    }

    public function setIdCtg(?Categorie $idCtg): self
    {
        $this->idCtg = $idCtg;

        return $this;
    }


}
