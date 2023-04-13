<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\ReclamationRepository;

#[ORM\Entity(repositoryClass: ReclamationRepository ::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $reclamationid;

    #[ORM\Column(length:255)]
    private ?string $sujet;

    #[ORM\Column(length:255)]
    private ?string $plainte;

    #[ORM\Column(name: "date", type: "date", nullable: false)]
    private \DateTime $date;
   
    #[ORM\ManyToOne(inversedBy:'User')]
    private ?User $userid;


    public function __construct()
    {
        $this->date = new \DateTime();
    }
    
    public function getReclamationid(): ?int
    {
        return $this->reclamationid;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getPlainte(): ?string
    {
        return $this->plainte;
    }

    public function setPlainte(string $plainte): self
    {
        $this->plainte = $plainte;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUserid(): ?User
    {
        return $this->userid;
    }

    public function setUserid(?User $userid): self
    {
        $this->userid = $userid;

        return $this;
    }


}
