<?php

namespace App\Controller\Comment;

use App\Entity\Article;
use App\Repository\CommentRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}/comments", methods={"GET"}, name="api_comments_list")
 */
final class GetCommentsListController
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
