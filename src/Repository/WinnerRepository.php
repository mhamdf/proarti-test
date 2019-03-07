<?php

namespace App\Repository;

use App\Entity\Winner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Winner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Winner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Winner[]    findAll()
 * @method Winner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WinnerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Winner::class);
    }

    // /**
    //  * @return Winner[] Returns an array of Winner objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Winner
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
