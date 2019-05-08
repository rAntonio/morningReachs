<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[] Returns an array of Article objects
    */
    
    public function findLast($page)
    {
        return $this->createQueryBuilder('Article')
            ->andWhere('Article.level >= 1')
            ->orderBy('Article.created', 'ASC')
            ->setFirstResult((($page - 1)*9))
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findPaginateByAuthor($page,$id)
    {
        return $this->createQueryBuilder('Article')
            ->andWhere('Article.idAuteur = :id')
            ->setParameter('id', $id)
            ->orderBy('Article.created', 'ASC')
            ->setFirstResult((($page - 1)*9))
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
        ;
    }
/*
    public function count()
    {
        return $this->createQueryBuilder('Article')
        ->select('COUNT(Article)')
        ->getQuery()
        ->getSingleScalarResult();
    }*/
    

    // /**
    //  * @return Article[] Returns an array of Article objects
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

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
