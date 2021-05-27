<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function listOfEventsQuery(){

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select event.* , user.username, user.last_name, 
            user.first_name from event inner join user on event.creator_id = user.id order by event.id desc';

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();

        } catch (DBALException $e) {
        }


        return $stmt->fetchAll();
    }

    public function eventsCreatedByUser($userId){

        return $this->createQueryBuilder('e')
            ->andWhere('e.creatorId = :val')
            ->setParameter('val', $userId)
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;


    }
    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
