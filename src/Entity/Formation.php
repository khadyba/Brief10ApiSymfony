<?php

namespace App\Entity;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\FormationController;
use App\Repository\FormationRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
#[ApiResource(operations: [
    new GetCollection(
        // name: 'app_formations', 
        // uriTemplate: '/api/formations_liste', 
        // controller: FormationController::class.'::index'
    ),
    new Post(
        name: 'app_formation', 
        uriTemplate: 'api/formation_create', 
        controller: FormationController::class.'::create'
    ),
    new Put(
        // name: 'app_formations', 
        // uriTemplate: '/api/formations_modif/{id}', 
        // controller: FormationController::class.'::update'
    ),
    new Delete(
        name: 'app_formations', 
        uriTemplate: '/api/formations_delete/{id}', 
        controller: FormationController::class.'::delete'
    )
])]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
  /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libellé est requis")
     */
    private ?string $libeller = null;

    #[ORM\Column(type: Types::TEXT)]
     /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description est requise")
     */
    private ?string $description = null;

    #[ORM\Column(length: 255)]
     /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value=0, message="La durée doit être supérieure à zéro")
     */
    private ?string $durer_formations = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $is_delete = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibeller(): ?string
    {
        return $this->libeller;
    }

    public function setLibeller(string $libeller): static
    {
        $this->libeller = $libeller;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDurerFormations(): ?string
    {
        return $this->durer_formations;
    }

    public function setDurerFormations(string $durer_formations): static
    {
        $this->durer_formations = $durer_formations;

        return $this;
    }

    public function isIsDelete(): ?bool
    {
        
        return $this->is_delete;
        

    }

    public function setIsDelete(bool $is_delete): static
    {
        $this->is_delete = $is_delete;

        return $this;
    }
}
