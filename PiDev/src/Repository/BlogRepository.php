<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Blog $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Blog $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Blog[] Returns an array of Blog objects
    //  */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :val')
            ->setParameter('val', $value)
            ->orderBy('b.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function countSousse(){
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere('LOWER(u.description) LIKE :val')
            ->setParameter('val','%'.'sousse'.'%')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countMahdia(){
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere('LOWER(u.description) LIKE :val')
            ->setParameter('val','%'.'mehdia'.'%')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countBizerte(){
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere('LOWER(u.description) LIKE :val')
            ->setParameter('val','%'.'bizerte'.'%')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countKef(){
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere('LOWER(u.description) LIKE :val')
            ->setParameter('val','%'.'kef'.'%')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countTozeur(){
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere('LOWER(u.description) LIKE :val')
            ->setParameter('val','%'.'tozeur'.'%')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countNabeul(){
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere('LOWER(u.description) LIKE :val')
            ->orWhere('LOWER(u.description) LIKE :vall')
            ->setParameter('val','%'.'nabeul'.'%')
            ->setParameter('vall','%'.'hammamet'.'%')
            ->getQuery()
            ->getSingleScalarResult();
    }
    /*
    public function findOneBySomeField($value): ?Blog
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
