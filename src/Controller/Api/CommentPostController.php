<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * CommentPostController.
 *
 * @Route("/api/articles/{slug}/comments", name="api_comment_post")
 * @Method("POST")
 * @View(statusCode=201)
 */
class CommentPostController
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
     * @param Article       $article
     *
     * @return array|FormInterface
     */
    public function __invoke(UserInterface $user, Request $request, Article $article)
    {
        /** @var User $user */
        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setArticle($article);

        $form = $this->factory->createNamed('comment', CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($comment);
            $this->manager->flush();

            return ['comment' => $comment];
        }

        return $form;
    }
}
