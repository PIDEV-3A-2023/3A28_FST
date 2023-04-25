<?php

namespace App\Repository;

use App\Entity\Statut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Statut>
 *
 * @method Statut|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statut|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statut[]    findAll()
 * @method Statut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statut::class);
    }

    public function save(Statut $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Statut $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }





    // public function findByStat($search)
    // {
    //     return $this->createQueryBuilder('s')
    //         ->andWhere('s.contenu LIKE :mot OR s.titre LIKE :mot')
    //         ->setParameter('mot', '%' . $search . '%')
    //         ->getQuery()
    //         ->getResult();
    // }

    //    /**
    //     * @return Statut[] Returns an array of Statut objects
    //     */
    public function findBytype($value): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.type = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function findByTitreOrContenu($search)
    {
        return $this->createQueryBuilder('f')
            ->where('f.titre LIKE :mot OR f.contenu LIKE :mot')
            ->setParameter('mot', '%' . $search . '%')

            ->getQuery()
            ->getResult();
    }

    function latest_posts()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT statut.titre,statut.created,statut.image  as co FROM statut GROUP BY statut.created ORDER BY statut.created desc LIMIT 2';
        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

   

}
