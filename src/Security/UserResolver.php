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
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @throws NoCurrentUserException
     *
     * @return User
     */
    public function getCurrentUser(): User
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        if (!($user instanceof User)) {
            throw new NoCurrentUserException();
        }

        return $user;
    }
}
