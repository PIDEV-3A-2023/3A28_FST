<?php

namespace App\Entity;

use App\Repository\RemiseRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Cartitem;
#[ORM\Entity(repositoryClass: RemiseRepository::class)]
class Remise
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $code = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $nb = null;

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getNb(): ?int
    {
        return $this->nb;
    }

    public function setNb(int $nb): self
    {
        $this->nb = $nb;
        return $this;
    }
}
