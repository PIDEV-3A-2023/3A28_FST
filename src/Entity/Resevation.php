<?php

namespace App\Entity;

use App\Repository\ResevationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResevationRepository::class)]
class Resevation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'resevations')]
    private ?Evenement $event_Id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventId(): ?Evenement
    {
        return $this->event_Id;
    }

    public function setEventId(?Evenement $event_Id): self
    {
        $this->event_Id = $event_Id;

        return $this;
    }
}
