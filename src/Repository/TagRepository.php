<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TagRepository extends ServiceEntityRepository
{
    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }
}
