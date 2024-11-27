<?php

declare(strict_types=1);

namespace App\Infra\Persistence\Doctrine\ORM\Entity;

use App\Infra\Persistence\Doctrine\ORM\Repository\FleetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FleetRepository::class)]
class Fleet
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\ManyToOne(inversedBy: 'fleet')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user_id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 40)]
    private string $label;

    #[ORM\Column]
    private \DateTimeImmutable $created_at;

    #[ORM\Column]
    private \DateTimeImmutable $updated_at;

    /**
     * @var Collection<int, Ressource>
     */
    #[ORM\OneToMany(targetEntity: Ressource::class, mappedBy: 'fleet_id', orphanRemoval: true)]
    private Collection $ressources;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Ressource>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): static
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
            $ressource->setFleetId($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): static
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getFleetId() === $this) {
                $ressource->setFleetId(null);
            }
        }

        return $this;
    }
}
