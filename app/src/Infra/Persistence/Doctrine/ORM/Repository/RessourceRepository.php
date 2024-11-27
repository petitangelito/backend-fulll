<?php

declare(strict_types=1);

namespace App\Infra\Persistence\Doctrine\ORM\Repository;

use App\Infra\Persistence\Doctrine\ORM\Entity\Ressource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ressource>
 *
 * * @method Ressource|null find(Uuid $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null)
 * * @method Ressource|null findOneBy(array $criteria, ?array $orderBy = null)
 * * @method Ressource[]    findAll()
 * * @method Ressource[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 */
class RessourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ressource::class);
    }

    public function save(Ressource $ressource, bool $flush = true): void
    {
        $this->getEntityManager()->persist($ressource);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ressource $ressource, bool $flush = true): void
    {
        $this->getEntityManager()->remove($ressource);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByPlateNumber(string $plateNumber, string $fleetId): ?Ressource
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.plate_number = :plateNumber')
            ->andWhere('r.fleet_id = :fleet')
            ->setParameter('plateNumber', $plateNumber)
            ->setParameter('fleet', $fleetId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
