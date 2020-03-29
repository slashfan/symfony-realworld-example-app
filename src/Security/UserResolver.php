<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Exception\NoCurrentUserException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * UserResolver.
 */
final class UserResolver
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @throws NoCurrentUserException
     */
    public function getCurrentUser(): User
    {
        $token = $this->tokenStorage->getToken();
        $user = $token !== null ? $token->getUser() : null;

        if (!($user instanceof User)) {
            throw new NoCurrentUserException();
        }

        return $user;
    }
}
