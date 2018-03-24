<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UsersPostController.
 *
 * @Route("/api/articles", name="api_articles_post")
 * @Method("POST")
 * @View(statusCode=201)
 */
class ArticlesPostController
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @param FormFactoryInterface   $factory
     * @param EntityManagerInterface $manager
     */
    public function __construct(FormFactoryInterface $factory, EntityManagerInterface $manager)
    {
        $this->factory = $factory;
        $this->manager = $manager;
    }

    /**
     * @param UserInterface $user
     * @param Request       $request
     *
     * @return array|FormInterface
     */
    public function __invoke(UserInterface $user, Request $request)
    {
        /** @var User $user */
        $article = new Article();
        $article->setAuthor($user);

        $form = $this->factory->createNamed('article', ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($article);
            $this->manager->flush();

            return ['article' => $article];
        }

        return $form;
    }
}
