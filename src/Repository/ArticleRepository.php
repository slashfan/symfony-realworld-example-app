<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
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
     * @param string|null $tag
     * @param string|null $authorUsername
     * @param string|null $favoritedByUsername
     *
     * @return int
     */
    public function getArticlesListCount(?string $tag, ?string $authorUsername, ?string $favoritedByUsername): int
    {
        try {
            return (int) $this
                ->getArticlesListQueryBuilder($tag, $authorUsername, $favoritedByUsername)
                ->select('count(a.id) as total')
                ->getQuery()
                ->getSingleScalarResult()
            ;
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * @param int         $offset
     * @param int         $limit
     * @param string|null $tag
     * @param string|null $authorUsername
     * @param string|null $favoritedByUsername
     *
     * @return Article[]
     */
    public function getArticlesList(int $offset, int $limit, ?string $tag, ?string $authorUsername, ?string $favoritedByUsername): array
    {
        return $this
            ->getArticlesListQueryBuilder($tag, $authorUsername, $favoritedByUsername)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param User $user
     *
     * @return int
     */
    public function getArticlesFeedCount(User $user): int
    {
        try {
            return (int) $this
                ->getArticlesFeedQueryBuilder($user)
                ->select('count(a.id) as total')
                ->getQuery()
                ->getSingleScalarResult()
            ;
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * @param User $user
     * @param int  $offset
     * @param int  $limit
     *
     * @return Article[]
     */
    public function getArticlesFeed(User $user, int $offset, int $limit): array
    {
        return $this
            ->getArticlesFeedQueryBuilder($user)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string|null $tag
     * @param string|null $authorUsername
     * @param string|null $favoritedByUsername
     *
     * @return QueryBuilder
     */
    private function getArticlesListQueryBuilder(?string $tag, ?string $authorUsername, ?string $favoritedByUsername): QueryBuilder
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->innerJoin('a.author', 'author')
            ->orderBy('a.id', 'desc')
        ;

        if ($tag) {
            $qb->innerJoin('a.tags', 't');
            $qb->andWhere('t.name = :tag');
            $qb->setParameter('tag', $tag);
        }

        if ($authorUsername) {
            $qb->andWhere('author.username = :author_username');
            $qb->setParameter('author_username', $authorUsername);
        }

        if ($favoritedByUsername) {
            $qb->innerJoin('a.favoritedBy', 'favoritedBy');
            $qb->andWhere('favoritedBy.username = :favoritedby_username');
            $qb->setParameter('favoritedby_username', $favoritedByUsername);
        }

        return $qb;
    }

    /**
     * @param User $user
     *
     * @return QueryBuilder
     */
    private function getArticlesFeedQueryBuilder(User $user): QueryBuilder
    {
        return $this
            ->createQueryBuilder('a')
            ->innerJoin('a.author', 'author')
            ->andWhere('author IN (:authors_ids)')
            ->setParameter('authors_ids', $user->getFolloweds())
            ->orderBy('a.id', 'desc')
        ;
    }
}
