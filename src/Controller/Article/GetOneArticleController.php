<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}", methods={"GET"}, name="api_articles_get")
 */
final class GetOneArticleController
{
    public function __invoke(Article $article): array
    {
        return ['article' => $article];
    }
}
