<?php

namespace App\Repository;

use App\Entity\Allowance;
use App\Entity\Decision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Decision>
 *
 * @method Decision|null find($id, $lockMode = null, $lockVersion = null)
 * @method Decision|null findOneBy(array $criteria, array $orderBy = null)
 * @method Decision[]    findAll()
 * @method Decision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DecisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Decision::class);
    }

    public function add(Allowance $allowance, bool $flush = false): void
    {
        $entity = new Decision();
        $entity->setAllowance($allowance);

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Decision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function extendDecisions()
    {
        $entityManager = $this->getEntityManager();

        /** @var AllowanceRepository $allowanceRepository */
        $allowanceRepository = $entityManager->getRepository(Allowance::class);
        $allowances = $allowanceRepository->getExpiredActiveAllowances();

        /** @var Allowance $allowance */
        foreach ($allowances as $allowance){
            $this->add($allowance, true);
        }

        return sizeof($allowances);
    }

    public function getTotalDistance(Decision $decision): float
    {
        $entityManager = $this->getEntityManager();

        $dql = /* @lang DQL */ "SELECT SUM(r.distance)
            FROM App\Entity\Ride r
            WHERE r.decision = :decisionId
            ";

        $query = $entityManager->createQuery($dql);
        $query->setParameter('decisionId', $decision->getId());

        return floatval($query->getSingleScalarResult());
    }

//    /**
//     * @return Decision[] Returns an array of Decision objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Decision
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}
