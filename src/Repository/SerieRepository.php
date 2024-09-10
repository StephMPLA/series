<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function findBestSeries()
    {
        /*
        //en DQL
        $entityManager = $this->getEntityManager();
        $dql = "
        SELECT s 
        FROM App\Entity\Serie s 
        WHERE s.popularity > 100
        AND s.vote > 8
        ORDER BY s.popularity ASC
        ";

        //Crée un objet DQL
        $query = $entityManager->createQuery($dql);
        //Nombre resultat max a récuperer
        //$query->setMaxResults(50);
        //$results = $query->getResult();
        //dump($results);
*/
         //Version QueryBuilder
        $queryBuilder = $this->createQueryBuilder('s');

        $queryBuilder->leftJoin('s.seasons', 'seasons')
        ->addSelect('seasons');

        $queryBuilder-> andWhere('s.popularity > 100');
        $queryBuilder-> andWhere('s.vote > 8');
        $queryBuilder->addOrderBy('s.popularity', 'DESC');
        $query = $queryBuilder->getQuery();

        $query->setMaxResults(50);

        $paginator = new Paginator($query, true);

        //$results = $query->getResult();
         return $paginator;

    }

    //    /**
    //     * @return Serie[] Returns an array of Serie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Serie
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
