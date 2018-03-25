<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}/comments", name="api_comments_list")
 * @Method("GET")
 */
final class CommentsListController
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @param CommentRepository $repository
     */
    public function __construct(CommentRepository $repository)
    {
        $this->commentRepository = $repository;
    }

    /**
     * @param Article $article
     *
     * @return array
     */
    public function __invoke(Article $article)
    {
        return ['comments' => $this->commentRepository->findBy(['article' => $article])];
    }
}
