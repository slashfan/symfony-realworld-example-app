<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * UserResolver.
 */
class UserResolver
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
     * @throws \Exception
     *
     * @return User
     */
    public function getCurrentUser(): User
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            throw new \Exception('No token found.');
        }

        $user = $token->getUser();

        if (null === $user || !$user instanceof User) {
            throw new \Exception('No user found.');
        }

        return $user;
    }
}
