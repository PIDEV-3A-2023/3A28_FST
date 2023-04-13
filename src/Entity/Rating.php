<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Repository\RatingRepository;

#[ORM\Entity(repositoryClass: RatingRepository ::class)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

   
    #[ORM\Column(nullable: true)]
    private ?int $rating;

  
    #[ORM\ManyToOne(inversedBy:'Produit')]
    private ?Produit $idProduit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $idProduit): self
    {
        $this->idProduit = $idProduit;

        return $this;
    }


}
