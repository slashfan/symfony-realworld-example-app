<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\AbstractController;
use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}/favorite", methods={"POST"}, name="api_article_favorite")
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class FavoriteArticleController extends AbstractController
{
    public function __invoke(Article $article): array
    {
        $user = $this->getCurrentUser();
        $user->addToFavorites($article);

        $this->getDoctrine()->getManager()->flush();

        return ['article' => $article];
    }
}
