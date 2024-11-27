<?php

declare(strict_types=1);

namespace App\Infra\Persistence\Doctrine\ORM\Entity;

use App\Infra\Persistence\Doctrine\ORM\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\ManyToOne(inversedBy: 'location')]
    #[ORM\JoinColumn(nullable: false)]
    private Ressource $ressource_id;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual(value: 90)]
    #[Assert\GreaterThanOrEqual(value: -90)]
    private float $lat;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual(value: 180)]
    #[Assert\GreaterThanOrEqual(value: -180)]
    private float $lng;

    #[ORM\Column(nullable: true)]
    private ?int $place_number = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getRessourceId(): ?Ressource
    {
        return $this->ressource_id;
    }

    public function setRessourceId(?Ressource $ressource_id): static
    {
        $this->ressource_id = $ressource_id;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): static
    {
        $this->lng = $lng;

        return $this;
    }

    public function getPlaceNumber(): ?int
    {
        return $this->place_number;
    }

    public function setPlaceNumber(?int $place_number): static
    {
        $this->place_number = $place_number;

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
}
