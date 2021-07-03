<?php

declare(strict_types=1);

namespace App\Controller\Registration;

use App\Controller\AbstractController;
use App\Entity\User;
use App\Form\UserType;
use FOS\RestBundle\Controller\Annotations\View;
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
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function __invoke(Request $request): array
    {
        $user = new User();

        $form = $this->createNamedForm('user', UserType::class, $user);
        $form->submit($request->request->get('user'));

        if ($form->isValid()) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return ['user' => $user];
        }

        return ['form' => $form];
    }
}
