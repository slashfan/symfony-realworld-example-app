<?php

declare(strict_types=1);

namespace App\Controller\Registration;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users", methods={"POST"}, name="api_users_post")
 *
 * @View(statusCode=201, serializerGroups={"me"})
 */
final class RegisterController extends AbstractController
{
    private FormFactoryInterface $formFactory;
    private UserPasswordHasherInterface $userPasswordHasher;
    private EntityManagerInterface $entityManager;

    public function __construct(
        FormFactoryInterface $formFactory,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ) {
        $this->formFactory = $formFactory;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): array
    {
        $user = new User();

        $form = $this->formFactory->createNamed('user', UserType::class, $user);
        $form->submit($request->request->get('user'));

        if ($form->isValid()) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return ['user' => $user];
        }

        return ['form' => $form];
    }
}
