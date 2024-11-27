<?php

declare(strict_types=1);

namespace App\Infra\Persistence\Doctrine\ORM\Repository;

use App\Infra\Persistence\Doctrine\ORM\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<User>
 *
 * * @method User|null find(Uuid $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null)
 * * @method User|null findOneBy(array $criteria, ?array $orderBy = null)
 * * @method User[]    findAll()
 * * @method User[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user, bool $flush = true): void
    {
        $this->getEntityManager()->persist($user);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $user, bool $flush = true): void
    {
        $this->getEntityManager()->remove($user);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
