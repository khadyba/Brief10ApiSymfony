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
    
    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Choice(choices={"accepté", "refusé"}, message="Le statut doit être 'accepté' ou 'refusé'")
     */
    
     #[ORM\Column(type: Types::STRING,options: ['default' => 'refuser'])]
    /**
     * @ORM\Column(type="string", length=10, options={"default": "refusé"})
     */
    private string $status = 'refusé';
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
    public function setRelatedEntity(?User $User): self
    {
        $this->relatedEntity = $User;

        return $this;
    }

    public function getRelatedUser(): ?User
    {
        return $this->relatedEntity;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
