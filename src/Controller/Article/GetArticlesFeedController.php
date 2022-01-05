<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Repository\ArticleRepository;
use App\Security\UserResolver;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/feed", methods={"GET"}, name="api_articles_feed")
 *
 * @QueryParam(name="limit", requirements="\d+", default="20")
 * @QueryParam(name="offset", requirements="\d+", default="0")
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class GetArticlesFeedController extends AbstractController
{
    private UserResolver $userResolver;
    private ArticleRepository $articleRepository;

    public function __construct(UserResolver $userResolver, ArticleRepository $repository)
    {
        $this->userResolver = $userResolver;
        $this->articleRepository = $repository;
    }

    public function __invoke(ParamFetcher $paramFetcher): array
    {
        $user = $this->userResolver->getCurrentUser();

        $offset = (int) $paramFetcher->get('offset');
        $limit = (int) $paramFetcher->get('limit');

        $articlesCount = $this->articleRepository->getArticlesFeedCount($user);
        $articles = $this->articleRepository->getArticlesFeed($user, $offset, $limit);

        return [
            'articlesCount' => $articlesCount,
            'articles' => $articles,
        ];
    }
}
