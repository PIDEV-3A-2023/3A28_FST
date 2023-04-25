<?php

namespace App\Repository;

use App\Entity\Workshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workshop>
 *
 * @method Workshop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workshop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workshop[]    findAll()
 * @method Workshop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkshopRepository extends ServiceEntityRepository
{

/**
     * Recherche les workshops en fonction du formulaire
     * @return void 
     */
    public function search($mots_cles = null, $categorie = null){
        $query = $this->createQueryBuilder('a');
        $query->where('a.active = 1');
        if($mots_cles != null){
            $query->andWhere('MATCH_AGAINST(a.titre, a.description) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots_cles);
        }
        if($categorie != null){
            $query->leftJoin('a.categories', 'c');
            $query->andWhere('c.id = :id')
                ->setParameter('id', $categorie);
        }
        return $query->getQuery()->getResult();
    }
    
    public function findAllCategories()
    {
        $qb = $this->createQueryBuilder('w');
        $qb->select('w.categorie')
           ->distinct(true);

        $result = $qb->getQuery()->getResult();

        $categories = [];
        foreach ($result as $row) {
            $categories[] = $row['categorie'];
        }

        return $categories;
    }

    public function filterByPrice($minPrice, $maxPrice)
{
    $qb = $this->createQueryBuilder('w');

    if ($minPrice !== null) {
        $qb->andWhere('w.prix >= :minPrice')
            ->setParameter('minPrice', $minPrice);
    }

    if ($maxPrice !== null) {
        $qb->andWhere('w.prix <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice);
    }

    return $qb->getQuery();
}




    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workshop::class);
    }

    public function save(Workshop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Workshop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Workshop[] Returns an array of Workshop objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Workshop
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
