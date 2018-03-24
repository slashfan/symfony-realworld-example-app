<?php

namespace App\Controller\Api;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserPutController.
 *
 * @Route("/api/user", name="api_users_put")
 * @Method("PUT")
 * @View(statusCode=200, serializerGroups={"me"})
 */
class UserPutController
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
    public function __construct(
        FormFactoryInterface $factory,
        EntityManagerInterface $manager
    ) {
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
        $form = $this->factory->createNamed('user', UserType::class, $user);
        $form->submit($request->request->get('user'), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            return ['user' => $user];
        }

        return $form;
    }
}
