<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * ArticlesUnfavoriteController.
 *
 * @Route("/api/articles/{slug}/favorites", name="api_article_unfavorite")
 * @Method("DELETE")
 * @View(statusCode=200)
 */
class ArticlesUnfavoriteController
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param UserInterface $user
     * @param Article       $article
     *
     * @return array
     */
    public function __invoke(UserInterface $user, Article $article)
    {
        /* @var User $user */

        $user->removeFromFavorites($article);
        $this->manager->flush();

        return ['article' => $article];
    }
}
