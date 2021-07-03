<?php

declare(strict_types=1);

namespace App\Controller\Comment;

use App\Controller\AbstractController;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}/comments", methods={"POST"}, name="api_comment_post")
 *
 * @View(statusCode=201)
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class CreateCommentController extends AbstractController
{
    public function __invoke(Request $request, Article $article): array
    {
        $user = $this->getCurrentUser();

        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setArticle($article);

        $form = $this->createNamedForm('comment', CommentType::class, $comment);
        $form->submit($request->request->get('comment'));

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();

            return ['comment' => $comment];
        }

        return ['form' => $form];
    }
}
