<?php

namespace App\Infra\Persistence\Doctrine\ORM\Entity;

use App\Infra\Persistence\Doctrine\ORM\Repository\RessourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\ManyToOne(inversedBy: 'ressource')]
    #[ORM\JoinColumn(nullable: false)]
    private Fleet $fleet_id;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 40)]
    private string $plate_number;

    #[ORM\Column(length: 40, nullable: true)]
    #[Assert\Length(max: 40)]
    private ?string $mode = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, Location>
     */
    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'ressource_id', orphanRemoval: true)]
    private Collection $locations;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFleetId(): ?Fleet
    {
        return $this->fleet_id;
    }

    public function setFleetId(?Fleet $fleet_id): static
    {
        $this->fleet_id = $fleet_id;

        return $this;
    }

    public function getPlateNumber(): ?string
    {
        return $this->plate_number;
    }

    public function setPlateNumber(string $plate_number): static
    {
        $this->plate_number = $plate_number;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): static
    {
        $this->mode = $mode;

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
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setRessourceId($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getRessourceId() === $this) {
                $location->setRessourceId(null);
            }
        }

        return $this;
    }
}
