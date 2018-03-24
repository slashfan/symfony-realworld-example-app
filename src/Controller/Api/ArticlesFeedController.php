<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\ArticleRepository;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * ArticlesFeedController.
 *
 * @Route("/api/articles/feed", name="api_articles_feed")
 * @Method("GET")
 *
 * @QueryParam(name="limit", requirements="\d+", default="20")
 * @QueryParam(name="offset", requirements="\d+", default="0")
 */
class ArticlesFeedController
{
    /**
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @param ArticleRepository $repository
     */
    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UserInterface $user
     * @param ParamFetcher  $paramFetcher
     *
     * @return array
     */
    public function __invoke(UserInterface $user, ParamFetcher $paramFetcher)
    {
        /** @var User $user */
        $articles = $this->repository->getFollowedUsersArticles(
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
