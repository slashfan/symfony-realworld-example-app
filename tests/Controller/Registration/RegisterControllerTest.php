<?php

declare(strict_types=1);

namespace App\Tests\Controller\Registration;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * UsersPostControllerTest.
 */
class RegisterControllerTest extends WebTestCase
{
    public function testBadRequestResponse(): void
    {
        $client = $this->createAnonymousApiClient();

        $client->request('POST', '/api/users');
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            [
                'username' => ['can\'t be blank'],
                'email' => ['can\'t be blank'],
                'password' => ['can\'t be blank'],
            ],
            json_decode($response->getContent(), true)
        );

        $client->request('POST', '/api/users', [], [], [], json_encode([
            'user' => [
                'username' => 'user1',
                'email' => 'user1@conduit.tld',
                'password' => 'pass',
            ],
        ]));
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            [
                'username' => ['has already been taken'],
                'email' => ['has already been taken'],
                'password' => ['is too short (minimum is 8 characters)'],
            ],
            json_decode($response->getContent(), true)
        );
    }

    public function testResponse(): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('POST', '/api/users', [], [], [], json_encode([
            'user' => [
                'username' => 'user1000',
                'email' => 'user1000@conduit.tld',
                'password' => 'password',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('email', $data['user']);
        $this->assertSame('user1000@conduit.tld', $data['user']['email']);
        $this->assertArrayHasKey('token', $data['user']);
    }
}
