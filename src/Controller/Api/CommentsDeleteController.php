<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * CommentsDeleteController.
 *
 * @Route("/api/articles/{slug}/comments/{id}", name="api_comments_delete")
 * @Method("DELETE")
 * @View(statusCode=204)
 */
class CommentsDeleteController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Comment $comment
     */
    public function __invoke(Comment $comment)
    {
        $this->manager->remove($comment);
        $this->manager->flush();
    }
}
