<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user", methods={"GET"}, name="api_user_get")
 *
 * @View(serializerGroups={"me"})
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class GetUserController extends AbstractController
{
    public function __invoke(): array
    {
        return ['user' => $this->getCurrentUser()];
    }
}
