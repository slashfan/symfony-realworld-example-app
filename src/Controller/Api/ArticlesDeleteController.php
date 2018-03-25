<?php

namespace App\Controller\Api;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{slug}", name="api_articles_delete")
 * @Method("DELETE")
 *
 * @Security("is_granted('ROLE_USER') and is_granted('OWNER', article)")
 */
final class ArticlesDeleteController
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
     * @param Article $article
     */
    public function __invoke(Article $article)
    {
        $this->entityManager->remove($article);
        $this->entityManager->flush();
    }
}
