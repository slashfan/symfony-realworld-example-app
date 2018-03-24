<?php

namespace App\Controller\Api;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * ArticlesDeleteController.
 *
 * @Route("/api/articles/{slug}", name="api_articles_delete")
 * @Method("DELETE")
 * @View(statusCode=204)
 * @IsGranted("OWNER", subject="article")
 */
class ArticlesDeleteController
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param UserInterface $user
     * @param Article       $article
     */
    public function __invoke(UserInterface $user, Article $article)
    {
        $this->manager->remove($article);
        $this->manager->flush();
    }
}
