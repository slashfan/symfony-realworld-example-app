<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Security\UserResolver;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles", name="api_articles_post")
 * @Method("POST")
 *
 * @View(statusCode=201)
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class ArticlesPostController
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserResolver
     */
    private $userResolver;

    /**
     * @param FormFactoryInterface   $formFactory
     * @param EntityManagerInterface $entityManager
     * @param UserResolver           $userResolver
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        UserResolver $userResolver
    ) {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->userResolver = $userResolver;
    }

    /**
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return array|FormInterface
     */
    public function __invoke(Request $request)
    {
        $user = $this->userResolver->getCurrentUser();

        $article = new Article();
        $article->setAuthor($user);

        $form = $this->formFactory->createNamed('article', ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return ['article' => $article];
        }

        return $form;
    }
}
