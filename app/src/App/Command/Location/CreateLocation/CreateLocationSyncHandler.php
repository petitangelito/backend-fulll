<?php

declare(strict_types=1);

namespace App\App\Command\Location\CreateLocation;

use App\Infra\Persistence\Doctrine\ORM\Entity\Location;
use App\Infra\Persistence\Doctrine\ORM\Repository\LocationRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateLocationSyncHandler
{
    public function __construct(private LocationRepository $locationRepository)
    {
    }

    public function __invoke(CreateLocationSync $message): void
    {
        $locationDto = $message->getLocationDto();
        $location = new Location();
        $location->setId($locationDto->getId());
        $location->setRessourceId($locationDto->ressource);
        $location->setLat($locationDto->latitude);
        $location->setLng($locationDto->longitude);
        $location->setPlaceNumber($locationDto->place_number);
        $location->setCreatedAt(new \DateTimeImmutable());
        $location->setUpdatedAt(new \DateTimeImmutable());

        $this->locationRepository->save($location);
    }
}
