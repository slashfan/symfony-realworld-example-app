<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\AbstractController;
use App\Repository\ArticleRepository;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles", methods={"GET"}, name="api_articles_list")
 *
 * @QueryParam(name="tag", requirements="[A-Za-z]+", nullable=true)
 * @QueryParam(name="author", requirements="[A-Za-z0-9]+", nullable=true)
 * @QueryParam(name="favorited", requirements="[A-Za-z0-9]+", nullable=true)
 * @QueryParam(name="limit", requirements="\d+", default="20")
 * @QueryParam(name="offset", requirements="\d+", default="0")
 */
final class GetArticlesListController extends AbstractController
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $repository)
    {
        $this->articleRepository = $repository;
    }

    public function __invoke(ParamFetcher $paramFetcher): array
    {
        $articlesCount = $this->articleRepository->getArticlesListCount(
            $paramFetcher->get('tag'),
            $paramFetcher->get('author'),
            $paramFetcher->get('favorited')
        );

        $articles = $this->articleRepository->getArticlesList(
            (int) $paramFetcher->get('offset'),
            (int) $paramFetcher->get('limit'),
            $paramFetcher->get('tag'),
            $paramFetcher->get('author'),
            $paramFetcher->get('favorited')
        );

        return [
            'articlesCount' => $articlesCount,
            'articles' => $articles,
        ];
    }
}
