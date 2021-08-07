<?php

declare(strict_types=1);

namespace App\Tests\TestCase;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    protected function createAnonymousApiClient(): KernelBrowser
    {
        return static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
        ]);
    }

    protected function createAuthenticatedApiClient(string $user = 'user1@conduit.tld'): KernelBrowser
    {
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => $user]);

        if (!$user instanceof User) {
            throw new \InvalidArgumentException('User not found.');
        }

        $token = static::getContainer()->get(JWTTokenManagerInterface::class)->create($user);
        static::ensureKernelShutdown();

        return static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Token ' . $token,
        ]);
    }
}
