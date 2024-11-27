<?php

declare(strict_types=1);

namespace App\App\Command\Ressource\CreateRessource;

use App\Infra\Persistence\Doctrine\ORM\Entity\Ressource;
use App\Infra\Persistence\Doctrine\ORM\Repository\RessourceRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateRessourceSyncHandler
{
    public function __construct(private readonly RessourceRepository $ressourceRepository)
    {
    }

    public function __invoke(CreateRessourceSync $message): void
    {
        $ressourceDto = $message->getRessourceDto();

        $ressource = new Ressource();
        $ressource->setId($ressourceDto->getId());
        $ressource->setFleetId($ressourceDto->fleet);
        $ressource->setPlateNumber($ressourceDto->plate_number);
        $ressource->setMode($ressourceDto->mode);
        $ressource->setCreatedAt(new \DateTimeImmutable());
        $ressource->setUpdatedAt(new \DateTimeImmutable());

        $this->ressourceRepository->save($ressource);
    }
}
