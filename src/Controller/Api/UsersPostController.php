<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * UsersPostController.
 *
 * @Route("/api/users", name="api_users_post")
 * @Method("POST")
 * @View(statusCode=201, serializerGroups={"me"})
 */
class UsersPostController
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
     * @param Request $request
     *
     * @return array|FormInterface
     */
    public function __invoke(Request $request)
    {
        $user = new User();

        $form = $this->factory->createNamed('user', UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
            $this->manager->persist($user);
            $this->manager->flush();

            return ['user' => $user];
        }

        return $form;
    }
}
