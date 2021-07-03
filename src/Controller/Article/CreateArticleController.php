<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\AbstractController;
use App\Entity\Article;
use App\Form\ArticleType;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles", methods={"POST"}, name="api_articles_post")
 *
 * @View(statusCode=201)
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class CreateArticleController extends AbstractController
{
    public function __invoke(Request $request): array
    {
        $user = $this->getCurrentUser();

        $article = new Article();
        $article->setAuthor($user);

        $form = $this->createNamedForm('article', ArticleType::class, $article);
        $form->submit($request->request->get('article'));

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($article);
            $this->getDoctrine()->getManager()->flush();

            return ['article' => $article];
        }

        return ['form' => $form];
    }
}
