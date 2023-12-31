<?php

namespace App\Repository;

use App\Entity\Candidater;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidater>
 *
 * @method Candidater|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidater|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidater[]    findAll()
 * @method Candidater[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidaterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidater::class);
    }

//    /**
//     * @return Candidater[] Returns an array of Candidater objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Candidater
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
