<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_ctg = null;

    #[ORM\OneToMany(mappedBy: 'id_ctg', targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCtg(): ?string
    {
        return $this->nom_ctg;
    }

    public function setNomCtg(string $nom_ctg): self
    {
        $this->nom_ctg = $nom_ctg;

        return $this;
    }

    public function __toString() {
        return $this->nom_ctg;
    }

        /**
     * @return Collection<int, Produit>
     */
    public function getIdp(): Collection
    {
        return $this->produits;
    }

    public function addIdp(Produit $idp): self
    {
        if (!$this->produits->contains($idp)) {
            $this->produits->add($idp);
            $idp->setIdctg($this);
        }

        return $this;
    }

    public function removeIdp(Produit $idp): self
    {
        if ($this->produits->removeElement($idp)) {
            // set the owning side to null (unless already changed)
            if ($idp->getIdctg() === $this) {
                $idp->setIdctg(null);
            }
        }

        return $this;
    }
}
