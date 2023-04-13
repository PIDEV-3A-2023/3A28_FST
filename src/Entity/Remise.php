<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Repository\RemiseRepository;

#[ORM\Entity(repositoryClass: RemiseRepository ::class)]
class Remise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $code;

     #[ORM\Column(length:255)]
    private ?string $owner;

    #[ORM\Column]
    private ?int $nb = 0;

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): self
    {
        $this->owner = $owner;

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
