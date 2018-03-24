<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * UsersLoginControllerTest.
 */
class UsersLoginControllerTest extends WebTestCase
{
    public function testResponse()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('POST', '/api/users/login', [], [], [], json_encode([
            'user' => [
                'email' => 'user1@realworld.tld',
                'password' => 'password',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('user', $data);
    }
}
