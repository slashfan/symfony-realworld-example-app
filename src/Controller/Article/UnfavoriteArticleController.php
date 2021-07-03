<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\AbstractController;
use App\Entity\Article;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}/favorite", methods={"DELETE"}, name="api_article_unfavorite")
 *
 * @View(statusCode=200)
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class UnfavoriteArticleController extends AbstractController
{
    public function __invoke(Article $article): array
    {
        $user = $this->getCurrentUser();
        $user->removeFromFavorites($article);

        $this->getDoctrine()->getManager()->flush();

        return ['article' => $article];
    }
}
