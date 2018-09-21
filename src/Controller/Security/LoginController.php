<?php

declare(strict_types=1);

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
    public function __invoke(): void
    {
        throw new \RuntimeException('Should not be reached.');
    }
}
