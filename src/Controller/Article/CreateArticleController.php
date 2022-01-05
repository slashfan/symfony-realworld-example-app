<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Security\UserResolver;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
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
    private UserResolver $userResolver;
    private FormFactoryInterface $formFactory;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserResolver $userResolver,
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory
    ) {
        $this->userResolver = $userResolver;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): array
    {
        $user = $this->userResolver->getCurrentUser();

        $article = new Article();
        $article->setAuthor($user);

        $form = $this->formFactory->createNamed('article', ArticleType::class, $article);
        $form->submit($request->request->get('article'));

        if ($form->isValid()) {
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return ['article' => $article];
        }

        return ['form' => $form];
    }
}
