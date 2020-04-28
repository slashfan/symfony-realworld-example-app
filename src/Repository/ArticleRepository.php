<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ArticleRepository extends ServiceEntityRepository
{
    /**
     * {@inheritdoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getArticlesListCount(?string $tag, ?string $authorUsername, ?string $favoritedByUsername): int
    {
        try {
            return (int) $this
                ->getArticlesListQueryBuilder($tag, $authorUsername, $favoritedByUsername)
                ->select('count(a.id) as total')
                ->getQuery()
                ->getSingleScalarResult()
            ;
        } catch (NonUniqueResultException | NoResultException $exception) {
            return 0;
        }
    }

    /**
     * @return Article[]
     */
    public function getArticlesList(
        int $offset,
        int $limit,
        ?string $tag,
        ?string $authorUsername,
        ?string $favoritedByUsername
    ): array {
        return $this
            ->getArticlesListQueryBuilder($tag, $authorUsername, $favoritedByUsername)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getArticlesFeedCount(User $user): int
    {
        try {
            return (int) $this
                ->getArticlesFeedQueryBuilder($user)
                ->select('count(a.id) as total')
                ->getQuery()
                ->getSingleScalarResult()
            ;
        } catch (NonUniqueResultException | NoResultException $exception) {
            return 0;
        }
    }

    /**
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

    private function getArticlesListQueryBuilder(
        ?string $tag,
        ?string $authorUsername,
        ?string $favoritedByUsername
    ): QueryBuilder {
        $queryBuilder = $this
            ->createQueryBuilder('a')
            ->innerJoin('a.author', 'author')
            ->orderBy('a.id', 'desc')
        ;

        if ($tag) {
            $queryBuilder->innerJoin('a.tags', 't');
            $queryBuilder->andWhere('t.name = :tag');
            $queryBuilder->setParameter('tag', $tag);
        }

        if ($authorUsername) {
            $queryBuilder->andWhere('author.username = :author_username');
            $queryBuilder->setParameter('author_username', $authorUsername);
        }

        if ($favoritedByUsername) {
            $queryBuilder->innerJoin('a.favoritedBy', 'favoritedBy');
            $queryBuilder->andWhere('favoritedBy.username = :favoritedby_username');
            $queryBuilder->setParameter('favoritedby_username', $favoritedByUsername);
        }

        return $queryBuilder;
    }

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
