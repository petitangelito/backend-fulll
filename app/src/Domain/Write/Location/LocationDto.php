<?php

declare(strict_types=1);

namespace App\Domain\Write\Location;

use App\Domain\Shared\Validator\IntegerPositiveNotZero;
use App\Domain\Shared\Validator\Latitude;
use App\Domain\Shared\Validator\Longitude;
use App\Infra\Persistence\Doctrine\ORM\Entity\Ressource;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

final class LocationDto implements \Stringable
{
    private Uuid $id;

    public function __construct(
        #[Assert\NotBlank]
        public Ressource $ressource,
        #[Assert\NotBlank]
        #[Latitude]
        public float $latitude,
        #[Assert\NotBlank]
        #[Longitude]
        public float $longitude,
        #[IntegerPositiveNotZero]
        public ?int $place_number,
    ) {
        $this->id = new UuidV4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return sprintf(
            'Location($id=%s, $lat=%s, $lng=%s)',
            $this->id,
            $this->latitude,
            $this->longitude
        );
    }
}
