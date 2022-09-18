<?php

namespace App\Repository;

use App\Entity\Allowance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Allowance>
 *
 * @method Allowance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Allowance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Allowance[]    findAll()
 * @method Allowance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllowanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Allowance::class);
    }

    public function add(Allowance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Allowance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getExpiredActiveAllowances()
    {
        $entityManager = $this->getEntityManager();

        $dql = /* @lang DQL */ "SELECT a 
            FROM App\Entity\Allowance a
            JOIN App\Entity\Decision d WITH d.allowance = a.id
            WHERE a.status = 'active'
            AND d.expiredAt = CURRENT_DATE()
            ";

        $query = $entityManager->createQuery($dql);
        return $query->getResult();
    }

//    /**
//     * @return Allowance[] Returns an array of Allowance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Allowance
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
