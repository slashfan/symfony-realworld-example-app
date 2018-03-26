<?php

namespace App\Tests\Controller\User;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * UserPutControllerTest.
 */
class UpdateUserControllerTest extends WebTestCase
{
    public function testAsAnonymous()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('PUT', '/api/user');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testEmptyRequestAsAuthenticated()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/user');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testBadRequestAsAuthenticated()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/user', [], [], [], json_encode([
            'user' => [
                'email' => '',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testAsAuthenticated()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/user', [], [], [], json_encode([
            'user' => [
                'email' => 'user1001@realworld.tld',
                'username' => 'user1001',
                'password' => 'password',
                'image' => 'http://user1001.tld',
                'bio' => 'Bio #1001',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('user', $data);
    }
}
