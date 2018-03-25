<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
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
    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param int         $offset
     * @param int         $limit
     * @param null|string $tag
     * @param null|string $author
     * @param null|string $favorited
     *
     * @return Article[]
     */
    public function getArticles(int $offset, int $limit, ?string $tag, ?string $author, ?string $favorited)
    {
        return $this
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'desc')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param User $user
     * @param int  $offset
     * @param int  $limit
     *
     * @return Article[]
     */
    public function getFollowedUsersArticles(User $user, int $offset, int $limit)
    {
        return $this
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'desc')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
