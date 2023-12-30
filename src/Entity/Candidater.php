<?php

namespace App\Entity;

use App\Entity\Formation;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CandidaterRepository;

#[ORM\Entity(repositoryClass: CandidaterRepository::class)]
class Candidater
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private ?User $relatedEntity = null;

    #[ORM\ManyToOne(targetEntity: Formation::class)]
    #[ORM\JoinColumn(name: "formation_id", referencedColumnName: "id")]
    private ?Formation $relatedFormation = null;

   
    public function getRelatedFormation(): ?Formation
    {
        return $this->relatedFormation;
    }

    public function setRelatedFormation(?Formation $formation): self
    {
        $this->relatedFormation = $formation;

        return $this;
    }


    #[ORM\Column(type: Types::ARRAY)]
    private array $status = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): array
    {
        return $this->status;
    }

    public function setStatus(array $status): static
    {
        $this->status = $status;

        return $this;
    }
}
