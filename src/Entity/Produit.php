<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]

class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message :"le nom est obligatoire")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message :"donnez une petite description de votre produit")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message :"le prix est obligatoire")]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $qte_stock = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categorie $id_ctg = null;

    #[ORM\Column(nullable: true)]
    private ?int $likes = null;

    #[ORM\Column(nullable: true)]
    private ?int $userid = null;

    public function getId(): ?int
    {
        return $this->id;
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
        return $this->qte_stock;
    }

    public function setQteStock(int $qte_stock): self
    {
        $this->qte_stock = $qte_stock;

        return $this;
    }

    public function getIdCtg(): ?categorie
    {
        return $this->id_ctg;
    }

    public function setIdCtg(?categorie $id_ctg): self
    {
        $this->id_ctg = $id_ctg;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(?int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getUserid(): ?int
    {
        return $this->userid;
    }

    public function setUserid(?int $userid): self
    {
        $this->userid = $userid;

        return $this;
    }
}
