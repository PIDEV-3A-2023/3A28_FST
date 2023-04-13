<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategorieRepository;

#[ORM\Entity(repositoryClass: CategorieRepository ::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idCtg;

    #[ORM\Column(length:255)]
    private ?string $nomCtg;

    public function getIdCtg(): ?int
    {
        return $this->idCtg;
    }

    public function getNomCtg(): ?string
    {
        return $this->nomCtg;
    }

    public function setNomCtg(string $nomCtg): self
    {
        $this->nomCtg = $nomCtg;

        return $this;
    }


}
