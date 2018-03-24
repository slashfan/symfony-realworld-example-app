<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserPutController.
 *
 * @Route("/api/user", name="api_users_put")
 * @Method("PUT")
 *
 * @Rest\View(
 *     statusCode=200,
 *     serializerGroups={"me"}
 * )
 */
class UserPutController
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @param FormFactoryInterface         $factory
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface       $manager
     */
    public function __construct(
        FormFactoryInterface $factory,
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $manager
    ) {
        $this->factory = $factory;
        $this->encoder = $encoder;
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
