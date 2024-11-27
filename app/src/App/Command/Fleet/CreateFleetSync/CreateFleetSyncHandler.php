<?php

declare(strict_types=1);

namespace App\App\Command\Fleet\CreateFleetSync;

use App\Infra\Persistence\Doctrine\ORM\Entity\Fleet;
use App\Infra\Persistence\Doctrine\ORM\Repository\FleetRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final class CreateFleetSyncHandler
{
    public function __construct(private readonly FleetRepository $fleetRepository)
    {
    }

    public function __invoke(CreateFleetSync $message): Uuid
    {
        $fleetDto = $message->getFleetDto();

        $fleet = new Fleet();
        $fleet->setId($fleetDto->getId());
        $fleet->setUserId($fleetDto->user);
        $fleet->setLabel($fleetDto->label);
        $fleet->setCreatedAt(new \DateTimeImmutable());
        $fleet->setUpdatedAt(new \DateTimeImmutable());

        $this->fleetRepository->save($fleet);

        return $fleet->getId();
    }
}
