<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BadwordsRepository;


 #[ORM\Entity(repositoryClass: BadwordsRepository ::class)]
class Badwords
{
  
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idBadwords;

    #[ORM\Column(length:600)]
    private ?string $word;

    public function getIdBadwords(): ?int
    {
        return $this->idBadwords;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(string $word): self
    {
        $this->word = $word;

        return $this;
    }


}
