<?php

declare(strict_types=1);

namespace App\Test;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * WebTestCase.
 */
class WebTestCase extends BaseWebTestCase
{
    /**
     * @return Client
     */
    protected function createAnonymousApiClient(): Client
    {
        return static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
        ]);
    }

    /**
     * @param string $user
     * @param string $password
     *
     * @return Client
     */
    protected function createAuthenticatedApiClient(string $user = 'user1@conduit.tld', string $password = 'password'): Client
    {
        return static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
            'PHP_AUTH_USER' => $user,
            'PHP_AUTH_PW' => $password,
        ]);
    }
}
