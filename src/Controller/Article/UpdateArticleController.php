<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\AbstractController;
use App\Entity\Article;
use App\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}", methods={"PUT"}, name="api_articles_put")
 *
 * @Security("is_granted('ROLE_USER') and is_granted('AUTHOR', article)")
 */
final class UpdateArticleController extends AbstractController
{
    public function __invoke(Request $request, Article $article): array
    {
        $form = $this->createNamedForm('article', ArticleType::class, $article);
        $form->submit($request->request->get('article'), false);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return ['article' => $article];
        }

        return ['form' => $form];
    }
}
