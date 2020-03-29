<?php

declare(strict_types=1);

namespace App\Tests\TestCase;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * WebTestCase.
 */
class WebTestCase extends BaseWebTestCase
{
    protected function createAnonymousApiClient(): KernelBrowser
    {
        return static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
        ]);
    }

    protected function createAuthenticatedApiClient(
        string $user = 'user1@conduit.tld',
        string $password = 'password'
    ): KernelBrowser {
        return static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
            'PHP_AUTH_USER' => $user,
            'PHP_AUTH_PW' => $password,
        ]);
    }
}
