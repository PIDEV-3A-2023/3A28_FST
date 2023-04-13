<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\EvenementRepository;

#[ORM\Entity(repositoryClass: EvenementRepository ::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column]
    #[ORM\Column(nullable: true)]
    private ?float $rating = 0;

    #[ORM\Column(length:255)]
    private ?string $titre;

    #[ORM\Column(length:255)]
    private ?string $localisation;

    #[ORM\Column(length:255)]
    private ?string $description;

    #[ORM\Column(name: "dateDebut", type: "date", nullable: false)]
    private \DateTime $dateDebut;
   

    #[ORM\Column(name: "dateFin", type: "date", nullable: false)]
    private \DateTime $dateFin;

    #[ORM\Column]
    private ?float $prix;

    #[ORM\Column(length:255,nullable: true)]

    private ?string $image;

    #[ORM\Column(length:255,nullable: true)]
   
    private ?string $categorie;

    #[ORM\Column]
    private ?int $nbplace;

   
    #[ORM\Column(nullable: true)]
    private ?string $ratingnumber = '0';

    #[ORM\Column(nullable: true)]
    private ?string $points;


    #[ORM\ManyToMany(targetEntity: "User", inversedBy: "event")]
    #[ORM\JoinTable(name: "reservation")]
    private $users;

    #[ORM\JoinColumn(name: "event_id", referencedColumnName: "id")]
    private $joinColumns;

    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "userId")]
    private $inverseJoinColumns;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

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

    public function getNbplace(): ?int
    {
        return $this->nbplace;
    }

    public function setNbplace(int $nbplace): self
    {
        $this->nbplace = $nbplace;

        return $this;
    }

    public function getRatingnumber(): ?int
    {
        return $this->ratingnumber;
    }

    public function setRatingnumber(?int $ratingnumber): self
    {
        $this->ratingnumber = $ratingnumber;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

}
