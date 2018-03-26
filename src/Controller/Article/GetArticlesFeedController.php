<?php

namespace App\Controller\Article;

use App\Repository\ArticleRepository;
use App\Security\UserResolver;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/feed", name="api_articles_feed")
 * @Method("GET")
 *
 * @QueryParam(name="limit", requirements="\d+", default="20")
 * @QueryParam(name="offset", requirements="\d+", default="0")
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class GetArticlesFeedController
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var UserResolver
     */
    private $userResolver;

    /**
     * @param ArticleRepository $repository
     * @param UserResolver      $userResolver
     */
    public function __construct(ArticleRepository $repository, UserResolver $userResolver)
    {
        $this->articleRepository = $repository;
        $this->userResolver = $userResolver;
    }

    /**
     * @param ParamFetcher $paramFetcher
     *
     * @throws \Exception
     *
     * @return array
     */
    public function __invoke(ParamFetcher $paramFetcher)
    {
        $user = $this->userResolver->getCurrentUser();
        $articles = $this->articleRepository->getFollowedUsersArticles(
            $user,
            (int) $paramFetcher->get('offset'),
            (int) $paramFetcher->get('limit')
        );
        $articlesCount = count($articles);

        return [
            'articles' => $articles,
            'articlesCount' => $articlesCount,
        ];
    }
}
