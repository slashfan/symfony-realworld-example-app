<?php

namespace App\Tests\Controller\User;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * UserGetControllerTest.
 */
class GetUserControllerTest extends WebTestCase
{
    public function testAsAnonymous()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('GET', '/api/user');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAsAuthenticated()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('GET', '/api/user');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('email', $data['user']);
        $this->assertSame('user1@realworld.tld', $data['user']['email']);
        $this->assertArrayHasKey('token', $data['user']);
    }
}
