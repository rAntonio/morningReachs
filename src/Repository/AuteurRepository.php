<?php

namespace App\Repository;

use App\Entity\Auteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Auteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auteur[]    findAll()
 * @method Auteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuteurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Auteur::class);
    }

    // /**
    //  * @return Auteur[] Returns an array of Auteur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    public function findOneByLog($name,$word): ?Auteur
    {
        return $this->createQueryBuilder('Auteur')
            ->where('Auteur.email = :mail')
            ->setParameter('mail', $name)
            ->andWhere('Auteur.password = :password')
            ->setParameter('password', md5($word))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
