<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}", methods={"PUT"}, name="api_articles_put")
 *
 * @Security("is_granted('ROLE_USER') and is_granted('AUTHOR', article)")
 */
final class UpdateArticleController
{
    private FormFactoryInterface $formFactory;

    private EntityManagerInterface $entityManager;

    public function __construct(FormFactoryInterface $factory, EntityManagerInterface $manager)
    {
        $this->formFactory = $factory;
        $this->entityManager = $manager;
    }

    public function __invoke(Request $request, Article $article): array
    {
        $form = $this->formFactory->createNamed('article', ArticleType::class, $article);
        $form->submit($request->request->get('article'), false);

        if ($form->isValid()) {
            $this->entityManager->flush();

            return ['article' => $article];
        }

        return ['form' => $form];
    }
}
