<?php

namespace App\Repository;

use App\Entity\Alumnado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Alumnado>
 */
class AlumnadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alumnado::class);
    }
    public function add(Alumnado $alumnado): void
    {
        $this-> getEntityManager()->persist($alumnado);
        $this-> getEntityManager()->flush();
    }
    public function delete(Alumnado $alumnado): void
    {
        $this-> getEntityManager()->remove($alumnado);
        $this-> getEntityManager()->flush();
    }
    //    /**
    //     * @return Alumnado[] Returns an array of Alumnado objects
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

    //    public function findOneBySomeField($value): ?Alumnado
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
