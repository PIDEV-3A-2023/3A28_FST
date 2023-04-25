<?php

namespace App\Repository;

use App\Entity\ReservationWorkshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservationWorkshop>
 *
 * @method ReservationWorkshop|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationWorkshop|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationWorkshop[]    findAll()
 * @method ReservationWorkshop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationWorkshopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationWorkshop::class);
    }

    public function save(ReservationWorkshop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReservationWorkshop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ReservationWorkshop[] Returns an array of ReservationWorkshop objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReservationWorkshop
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
