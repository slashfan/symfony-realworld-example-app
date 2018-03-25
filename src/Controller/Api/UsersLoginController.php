<?php

namespace App\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users/login", name="api_users_login")
 * @Method("POST")
 */
final class UsersLoginController
{
    /**
     * @throws \RuntimeException
     */
    public function __invoke()
    {
        throw new \RuntimeException('Should not be reached.');
    }
}
