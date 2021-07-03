<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\AbstractController;
use App\Form\UserType;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user", methods={"PUT"}, name="api_users_put")
 *
 * @View(statusCode=200, serializerGroups={"me"})
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class UpdateUserController extends AbstractController
{
    public function __invoke(Request $request): array
    {
        $user = $this->getCurrentUser();

        $form = $this->createNamedForm('user', UserType::class, $user);
        $form->submit($request->request->get('user'), false);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return ['user' => $user];
        }

        return ['form' => $form];
    }
}
