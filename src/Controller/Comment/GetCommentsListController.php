<?php

declare(strict_types=1);

namespace App\Controller\Comment;

use App\Entity\Article;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}/comments", methods={"GET"}, name="api_comments_list")
 */
final class GetCommentsListController extends AbstractController
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $repository)
    {
        $this->commentRepository = $repository;
    }

    public function __invoke(Article $article): array
    {
        return [
            'comments' => $this->commentRepository->findBy(['article' => $article]),
        ];
    }
}
