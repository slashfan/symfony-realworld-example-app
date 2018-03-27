<?php

namespace App\Controller\Article;

use App\Entity\Article;
use App\Security\UserResolver;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}/favorite", name="api_article_unfavorite")
 * @Method("DELETE")
 *
 * @View(statusCode=200)
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class UnfavoriteArticleController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserResolver
     */
    private $userResolver;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserResolver           $userResolver
     */
    public function __construct(EntityManagerInterface $entityManager, UserResolver $userResolver)
    {
        $this->entityManager = $entityManager;
        $this->userResolver = $userResolver;
    }

    /**
     * @param Article $article
     *
     * @throws \Exception
     *
     * @return array
     */
    public function __invoke(Article $article)
    {
        $user = $this->userResolver->getCurrentUser();
        $user->removeFromFavorites($article);
        $this->entityManager->flush();

        return ['article' => $article];
    }
}
