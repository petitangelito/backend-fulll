<?php

declare(strict_types=1);

namespace App\Infra\Persistence\Doctrine\ORM\Repository;

use App\Infra\Persistence\Doctrine\ORM\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Location>
 *
 * * @method Location|null find(Uuid $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null)
 * * * @method Location|null findOneBy(array $criteria, ?array $orderBy = null)
 * * * @method Location[]    findAll()
 * * * @method Location[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function save(Location $location, bool $flush = true): void
    {
        $this->getEntityManager()->persist($location);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Location $location, bool $flush = true): void
    {
        $this->getEntityManager()->remove($location);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByLatLng(string $ressourceId, float $lat, float $long): ?Location
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.ressource_id = :ressourceId')
            ->andWhere('l.lat = :lat')
            ->andWhere('l.lng = :lng')
            ->setParameter('ressourceId', $ressourceId)
            ->setParameter('lat', $lat)
            ->setParameter('lng', $long)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
