<?php

namespace App\Tests\Controller\Registration;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * UsersPostControllerTest.
 */
class RegisterControllerTest extends WebTestCase
{
    public function testResponse()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('POST', '/api/users', [], [], [], json_encode([
            'user' => [
                'username' => 'user1000',
                'email' => 'user1000@realworld.tld',
                'password' => 'password',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('email', $data['user']);
        $this->assertSame('user1000@realworld.tld', $data['user']['email']);
        $this->assertArrayHasKey('token', $data['user']);
    }
}
