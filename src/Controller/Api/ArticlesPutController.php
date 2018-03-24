<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ArticlesPutController.
 *
 * @Route("/api/articles/{slug}", name="api_articles_put")
 * @Method("PUT")
 * @IsGranted("OWNER", subject="article")
 */
class ArticlesPutController
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
     * @param Request $request
     * @param Article $article
     *
     * @return array|FormInterface
     */
    public function __invoke(Request $request, Article $article)
    {
        $form = $this->factory->createNamed('article', ArticleType::class, $article);
        $form->submit($request->request->get('article'), false);

        if ($form->isValid()) {
            $this->manager->flush();

            return ['article' => $article];
        }

        return $form;
    }
}
