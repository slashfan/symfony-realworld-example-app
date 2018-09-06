<?php

namespace App\Controller\Security;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users/login", methods={"POST"}, name="api_users_login")
 */
final class LoginController
{
    /**
     * @throws \RuntimeException
     */
    public function __invoke()
    {
        throw new \RuntimeException('Should not be reached.');
    }
}
