<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationWorkshopRepository;

#[ORM\Entity(repositoryClass: ReservationWorkshopRepository ::class)]
class ReservationWorkshop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idReservation;

    #[ORM\Column(length:255)]
    private ?string $categorie;

   

     #[ORM\ManyToOne(inversedBy:'Workshop')]
    private ?Workshop $idWorkshop;

   
    #[ORM\ManyToOne(inversedBy:'User')]
    private ?User $idUser;

    public function getIdReservation(): ?int
    {
        return $this->idReservation;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getIdWorkshop(): ?Workshop
    {
        return $this->idWorkshop;
    }

    public function setIdWorkshop(?Workshop $idWorkshop): self
    {
        $this->idWorkshop = $idWorkshop;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
