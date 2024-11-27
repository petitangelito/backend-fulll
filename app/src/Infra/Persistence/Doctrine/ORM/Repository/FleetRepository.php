<?php

declare(strict_types=1);

namespace App\Infra\Persistence\Doctrine\ORM\Repository;

use App\Infra\Persistence\Doctrine\ORM\Entity\Fleet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Fleet>
 *
 * * @method Fleet|null find(Uuid $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null)
 * * @method Fleet|null findOneBy(array $criteria, ?array $orderBy = null)
 * * @method Fleet[]    findAll()
 * * @method Fleet[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 */
class FleetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fleet::class);
    }

    public function save(Fleet $fleet, bool $flush = true): void
    {
        $this->getEntityManager()->persist($fleet);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fleet $fleet, bool $flush = true): void
    {
        $this->getEntityManager()->remove($fleet);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
