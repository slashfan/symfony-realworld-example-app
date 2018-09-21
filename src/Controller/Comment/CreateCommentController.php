<?php

declare(strict_types=1);

namespace App\Controller\Comment;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Security\UserResolver;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}/comments", methods={"POST"}, name="api_comment_post")
 *
 * @View(statusCode=201)
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class CreateCommentController
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
     * @param Article $article
     *
     * @throws \Exception
     *
     * @return array|FormInterface
     */
    public function __invoke(Request $request, Article $article)
    {
        $user = $this->userResolver->getCurrentUser();

        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setArticle($article);

        $form = $this->formFactory->createNamed('comment', CommentType::class, $comment);
        $form->submit($request->request->get('comment'));

        if ($form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return ['comment' => $comment];
        }

        return $form;
    }
}
