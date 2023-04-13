<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\CommentaireRepository;

#[ORM\Entity(repositoryClass: CommentaireRepository ::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idC;

    #[ORM\Column(length:255)]
    private ?string $username;

    #[ORM\Column(length:600)]
    private ?string $description;

    #[ORM\Column(name: "date_ajout", type: "date", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]

    private ?\DateTime $date_ajout = null;

   
    #[ORM\ManyToOne(inversedBy:'Statut')]
    private ?Statut $idS;

    public function getIdC(): ?int
    {
        return $this->idC;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->date_ajout;
    }

    public function setDateAjout(?\DateTimeInterface $dateAjout): self
    {
        $this->date_ajout = $dateAjout;

        return $this;
    }

    public function getIdS(): ?Statut
    {
        return $this->idS;
    }

    public function setIdS(?Statut $idS): self
    {
        $this->idS = $idS;

        return $this;
    }


}
