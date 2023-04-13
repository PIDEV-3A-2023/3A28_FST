<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Repository\FeedbackRepository;

#[ORM\Entity(repositoryClass: FeedbackRepository ::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length:255)]
    private ?string $text;

   
    #[ORM\ManyToOne(inversedBy:'Evenement')]
    private ?Evenement $idEv;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getIdEv(): ?Evenement
    {
        return $this->idEv;
    }

    public function setIdEv(?Evenement $idEv): self
    {
        $this->idEv = $idEv;

        return $this;
    }


}
