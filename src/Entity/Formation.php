<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\FormationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
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
