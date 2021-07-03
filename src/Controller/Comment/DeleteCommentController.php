<?php

declare(strict_types=1);

namespace App\Controller\Comment;

use App\Controller\AbstractController;
use App\Entity\Comment;
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
final class DeleteCommentController extends AbstractController
{
    public function __invoke(Comment $comment): void
    {
        $this->getDoctrine()->getManager()->remove($comment);
        $this->getDoctrine()->getManager()->flush();
    }
}
