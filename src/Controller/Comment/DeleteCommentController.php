<?php

declare(strict_types=1);

namespace App\Controller\Comment;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}/comments/{id}", methods={"DELETE"}, name="api_comments_delete")
 *
 * @View(statusCode=204)
 *
 * @Security("is_granted('ROLE_USER') and is_granted('AUTHOR', comment)")
 */
final class DeleteCommentController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    public function __invoke(Comment $comment): void
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }
}
