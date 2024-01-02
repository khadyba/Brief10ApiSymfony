<?php

namespace App\Entity;

use App\Entity\Formation;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\CandidaterController;
use App\Repository\CandidaterRepository;

use function PHPSTORM_META\type;

#[ORM\Entity(repositoryClass: CandidaterRepository::class)]
#[ApiResource(operations: [
    new GetCollection(
        name: 'candidature_lister', 
        uriTemplate: 'api/lister', 
        controller: CandidaterController::class.'::listerCandidatures'
    ),
    new Put(
        name: 'candidature_accepter', 
        uriTemplate: 'api/accepter/{id}', 
        controller: CandidaterController::class.'::accepterCandidature'
    ),
    new GetCollection(
        name: 'candidature_lister_acceptees', 
        uriTemplate: 'api/lister_acceptees', 
        controller: CandidaterController::class.'::listerCandidaturesAcceptees'
    ),
    new GetCollection(
        name: 'candidature_lister_refusees', 
        uriTemplate: 'api/lister_refusees', 
        controller: CandidaterController::class.'::listerCandidaturesRefusees'
    ),
    new Post(
        name: 'candidature_enregistrer', 
        uriTemplate: '/enregistrer/{id}', 
        controller: CandidaterController::class.'::enregistrerCandidature'
    )])]
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

    public function getRelatedEntity(): ?User
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
