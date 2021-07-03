<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\AbstractController;
use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}", methods={"DELETE"}, name="api_articles_delete")
 *
 * @Security("is_granted('ROLE_USER') and is_granted('AUTHOR', article)")
 */
final class DeleteArticleController extends AbstractController
{
    public function __invoke(Article $article): void
    {
        $this->getDoctrine()->getManager()->remove($article);
        $this->getDoctrine()->getManager()->flush();
    }
}
