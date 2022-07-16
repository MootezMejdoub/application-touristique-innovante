<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reservation $entity, bool $flush = true): void
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
    public function remove(Reservation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws OptimisticLockException
     *
     */

    public function findTitle() {

        $em=$this->getEntityManager();
       // $query=$em->createQuery('SELECT u.nom,u.prenom,p.titre ,r.nbrPlace from (reservation r JOIN utilisateur u ON r.idClient=u.id) JOIN plan p ON r.idPlan=p.id');

        $query=$em->createQuery('select p.titre 

            from App\Entity\Reservation r 
            join App\Entity\Plan p 
            where r.idplan=p.id
            ');


            return $query->getResult();



    }

    public function addRes(){
        $em=$this->getEntityManager();
        $query=$em->createQuery('UPDATE reservation r INNER JOIN utilisateur u ON r.idClient=u.id INNER JOIN plan p ON r.idPlan=p.id INNER JOIN facture f ON r.idClient=f.idClient SET nbrPlace=?,f.date=?,r.dateDebut=?,r.dateFin=?');
        return $query->getResult();


    }


    public function updateRes(){

        $em=$this->getEntityManager();
        $query=$em->createQuery('UPDATE reservation r INNER JOIN utilisateur u ON r.idClient=u.id INNER JOIN plan p ON r.idPlan=p.id INNER JOIN facture f ON r.idClient=f.idClient SET p.titre=,r.nbrPlace=,f.date=,r.dateDebut=,r.dateFin= WHERE u.nom LIKE ');
        return $query->getResult();

    }

    public function deleteRes(){
        $em=$this->getEntityManager();
        $query=$em->createQuery('');
        return $query->getResult();


    }



























































}
