<?php

namespace App\Controller\Article;

use App\Repository\ArticleRepository;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles", name="api_articles_list")
 * @Method("GET")
 *
 * @QueryParam(name="tag", requirements="[A-Za-z]+", nullable=true)
 * @QueryParam(name="author", requirements="[A-Za-z0-9]+", nullable=true)
 * @QueryParam(name="favorited", requirements="[A-Za-z0-9]+", nullable=true)
 * @QueryParam(name="limit", requirements="\d+", default="20")
 * @QueryParam(name="offset", requirements="\d+", default="0")
 */
final class GetArticlesListController
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @param ArticleRepository $repository
     */
    public function __construct(ArticleRepository $repository)
    {
        $this->articleRepository = $repository;
    }

    /**
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function __invoke(ParamFetcher $paramFetcher)
    {
        $articles = $this->articleRepository->getArticles(
            (int) $paramFetcher->get('offset'),
            (int) $paramFetcher->get('limit'),
            $paramFetcher->get('tag'),
            $paramFetcher->get('author'),
            $paramFetcher->get('favorited')
        );

        $articlesCount = count($articles);

        return [
            'articles' => $articles,
            'articlesCount' => $articlesCount,
        ];
    }
}
