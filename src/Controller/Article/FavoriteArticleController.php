<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Entity\Article;
use App\Security\UserResolver;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}/favorite", methods={"POST"}, name="api_article_favorite")
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class FavoriteArticleController
{
    private EntityManagerInterface $entityManager;

    private UserResolver $userResolver;

    public function __construct(EntityManagerInterface $manager, UserResolver $userResolver)
    {
        $this->entityManager = $manager;
        $this->userResolver = $userResolver;
    }

    public function __invoke(Article $article): array
    {
        $user = $this->userResolver->getCurrentUser();
        $user->addToFavorites($article);
        $this->entityManager->flush();

        return ['article' => $article];
    }
}
