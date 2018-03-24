<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserGetController.
 *
 * @Route("/api/user", name="api_user_get")
 * @Method("GET")
 *
 * @Rest\View(serializerGroups={"me"})
 */
class UserGetController
{
    /**
     * @param UserInterface $user
     *
     * @return array
     */
    public function __invoke(UserInterface $user)
    {
        return ['user' => $user];
    }
}
