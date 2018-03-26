<?php

namespace App\Controller\Comment;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}/comments/{id}", name="api_comments_delete")
 * @Method("DELETE")
 *
 * @View(statusCode=204)
 *
 * @Security("is_granted('ROLE_USER') and is_granted('AUTHOR', comment)")
 */
final class DeleteCommentController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    /**
     * @param Comment $comment
     */
    public function __invoke(Comment $comment)
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }
}
