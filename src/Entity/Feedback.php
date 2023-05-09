<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("feedbacks")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("feedbacks")]
    #[Assert\NotBlank(message: "le text est obligatoire")]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'feedback')]
    #[Groups("feedbacks")]
    private ?Evenement $id_Ev = null;

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
        return $this->id_Ev;
    }

    public function setIdEv(?Evenement $id_Ev): self
    {
        $this->id_Ev = $id_Ev;

        return $this;
    }
}
