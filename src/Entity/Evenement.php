<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $rating = 0;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "le titre est obligatoire")]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "la localisation est obligatoire")]
    private ?string $localisation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "la description est obligatoire")]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "la date est obligatoire")]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "la date est obligatoire")]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "le prix est obligatoire")]
    private ?float $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $categorie = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "le nombre des places est obligatoire")]
    private ?int $nbPlace = null;

    #[ORM\Column(nullable: true)]
    private ?int $ratingNumber = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $points = null;

    #[ORM\OneToMany(mappedBy: 'id_Ev', targetEntity: Feedback::class)]
    private Collection $feedback;

    #[ORM\OneToMany(mappedBy: 'event_Id', targetEntity: Resevation::class)]
    private Collection $resevations;

    public function __construct()
    {
        $this->feedback = new ArrayCollection();
        $this->resevations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;

        return $this;
    }

    public function getRatingNumber(): ?int
    {
        return $this->ratingNumber;
    }

    public function setRatingNumber(?int $ratingNumber): self
    {
        $this->ratingNumber = $ratingNumber;

        return $this;
    }

    public function getPoints(): ?string
    {
        return $this->points;
    }

    public function setPoints(?string $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback->add($feedback);
            $feedback->setIdEv($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getIdEv() === $this) {
                $feedback->setIdEv(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Resevation>
     */
    public function getResevations(): Collection
    {
        return $this->resevations;
    }

    public function addResevation(Resevation $resevation): self
    {
        if (!$this->resevations->contains($resevation)) {
            $this->resevations->add($resevation);
            $resevation->setEventId($this);
        }

        return $this;
    }

    public function removeResevation(Resevation $resevation): self
    {
        if ($this->resevations->removeElement($resevation)) {
            // set the owning side to null (unless already changed)
            if ($resevation->getEventId() === $this) {
                $resevation->setEventId(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getTitre(); // Replace getTitle with the property that you want to display
    }
}
