<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Form\UserType;
use App\Security\UserResolver;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user", methods={"PUT"}, name="api_users_put")
 *
 * @View(statusCode=200, serializerGroups={"me"})
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class UpdateUserController
{
    private FormFactoryInterface $formFactory;

    private EntityManagerInterface $entityManager;

    private UserResolver $userResolver;

    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        UserResolver $userResolver
    ) {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->userResolver = $userResolver;
    }

    public function __invoke(Request $request): array
    {
        $user = $this->userResolver->getCurrentUser();

        $form = $this->formFactory->createNamed('user', UserType::class, $user);
        $form->submit($request->request->get('user'), false);

        if ($form->isValid()) {
            $this->entityManager->flush();

            return ['user' => $user];
        }

        return ['form' => $form];
    }
}
