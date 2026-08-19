<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function updateBlockByIds(array $ids, bool $block): int
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.isBlocked', ':block')
            ->where('u.id IN (:ids)')
            ->setParameter('block', $block)
            ->setParameter('ids', $ids)
            ->getQuery()
            ->execute();
    }
    public function deleteByIds(array $ids, bool $onlyUnverified = false): int
    {
        $qb = $this->createQueryBuilder('u')
            ->delete()
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $ids);
        if ($onlyUnverified) {
            $qb->andWhere('u.isVerified = :unverifiedStatus')
            ->setParameter('unverifiedStatus', false);
        }

        return $qb->getQuery()->execute();
    }
}
