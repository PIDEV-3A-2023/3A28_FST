<?php

namespace App\Repository;

use App\Entity\Workshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;
use Doctrine\ORM\EntityManagerInterface;

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

public function getDataForChart(): array
{
    $query = $this->createQueryBuilder('w')
        ->select('MONTH(w.date) as month, COUNT(w.id) as count')
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->getQuery();

    $results = $query->getResult();

    $data = array();
    foreach ($results as $result) {
        $data[] = $result['count'];
    }

    return $data;
}
public function countBy($attribute, $value)
{
    return $this->createQueryBuilder('w')
        ->select('COUNT(w)')
        ->where("w.$attribute = :value")
        ->setParameter('value', $value)
        ->getQuery()
        ->getSingleScalarResult();
}

public function countReservationsByWorkshop()
{
    return $this->createQueryBuilder('w')
        ->leftJoin('w.reservations', 'r')
        ->leftJoin('r.workshops', 'ws')
        ->select('w.id as workshop_id, ws.titre as workshop_titre, COUNT(r.id) AS reservations_count')
        ->groupBy('w.id')
        ->getQuery()
        ->getArrayResult();
}

public function sms(){
    // Your Account SID and Auth Token from twilio.com/console
            $sid = 'AC38c813ea93ef90478a582711653b1ea7';
            $auth_token = '4117e65b311ae9691a1f5eec8dc572da';
    // In production, these should be environment variables. E.g.:
    // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
    // A Twilio number you own with SMS capabilities
            $twilio_number = "+16203929372";
    
            $client = new Client($sid, $auth_token);
            $client->messages->create(
            // the number you'd like to send the message to
                '+21692278964',
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => '+16203929372',
                    // the body of the text message you'd like to send
                    'body' => 'Votre réservation a été effectuée avec succes'
                ]
            );
        }


public function searchByCategorie($categorie = null, $nom_artiste= null)
{
    $queryBuilder = $this->createQueryBuilder('a');

    if ($categorie) {
        $queryBuilder->andWhere('a.categorie LIKE :categorie')
            ->setParameter('categorie', '%'.$categorie.'%');
    }

    if ($nom_artiste ) {
        $queryBuilder->andWhere('a.nom_artiste  = :nom_artiste ')
            ->setParameter('nom_artiste ', $nom_artiste );
    }


    $query = $queryBuilder->getQuery();
    return $query->getResult();
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
