<?php

namespace App\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * WebTestCase.
 */
class WebTestCase extends BaseWebTestCase
{
    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAnonymousApiClient()
    {
        return static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
        ]);
    }

    /**
     * @param string $user
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedApiClient($user = 'user1@conduit.tld', $password = 'password')
    {
        return $this->createClient([], [
            'CONTENT_TYPE' => 'application/json',
            'PHP_AUTH_USER' => $user,
            'PHP_AUTH_PW' => $password,
        ]);
    }
}
